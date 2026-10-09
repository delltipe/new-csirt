<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PublicationImageService
{
    /**
     * Storage directory for generated derivatives.
     */
    protected string $baseDir;

    /**
     * In-memory cache manifest.
     */
    protected ?array $manifest = null;

    /**
     * Trusted hostnames allowed for remote image fetching.
     */
    protected array $trustedHosts = [
        'csirt.jakarta.go.id',
        'jakarta.go.id',
        'images.unsplash.com',
        'localhost',
        '127.0.0.1',
    ];

    /**
     * Variant specification by publication category.
     */
    protected array $variantSpecs = [
        'news' => [
            'thumb'  => ['width' => 600, 'quality' => 82],
            'detail' => ['width' => 1200, 'quality' => 82],
        ],
        'warnings' => [
            'thumb'  => ['width' => 600, 'quality' => 82],
            'detail' => ['width' => 1200, 'quality' => 82],
        ],
        'infographics' => [
            'thumb'  => ['width' => 600, 'quality' => 85],
            'detail' => ['width' => 1200, 'quality' => 85],
        ],
        'events' => [
            'thumb'  => ['width' => 600, 'quality' => 82],
            'detail' => ['width' => 800, 'quality' => 82],
        ],
    ];

    public function __construct(?string $baseDir = null)
    {
        $this->baseDir = $baseDir ?: public_path('images/optimized');
    }

    /**
     * Verify GD extension and WebP capability.
     */
    public function isSupported(): bool
    {
        return extension_loaded('gd') && function_exists('imagewebp');
    }

    /**
     * Get variant specifications for a category.
     */
    public function getVariantSpecs(string $type): array
    {
        return $this->variantSpecs[$type] ?? [
            'thumb'  => ['width' => 600, 'quality' => 82],
            'detail' => ['width' => 1200, 'quality' => 82],
        ];
    }

    /**
     * Resolve the best available image URL for a model or publication item.
     * Fallback chain:
     * 1. Modern WebP derivative in public/images/optimized/{type}/{id}_{variant}.webp
     * 2. Legacy WebP derivative in public/images/berita-optimized or gallery-optimized
     * 3. Original canonical URL
     */
    public function getUrl(mixed $model, string $variant = 'thumb'): ?string
    {
        if (!$model) {
            return null;
        }

        $type = $this->getTypeForModel($model);
        $id = $this->getIdForModel($model);
        $originalUrl = $this->getOriginalUrlForModel($model);

        // 1. Check optimized derivative
        if ($id !== null) {
            $derivativeRelPath = "images/optimized/{$type}/{$id}_{$variant}.webp";
            $derivativeFullPath = public_path($derivativeRelPath);

            if (file_exists($derivativeFullPath)) {
                return asset($derivativeRelPath);
            }

            // 2. Legacy fallback for thumbnail variant
            if ($variant === 'thumb') {
                if ($type === 'news') {
                    $legacyPath = public_path("images/berita-optimized/{$id}.webp");
                    if (file_exists($legacyPath)) {
                        return asset("images/berita-optimized/{$id}.webp");
                    }
                } elseif ($type === 'events') {
                    $legacyPath = public_path("images/gallery-optimized/{$id}.webp");
                    if (file_exists($legacyPath)) {
                        return asset("images/gallery-optimized/{$id}.webp");
                    }
                }
            }
        }

        // 3. Original fallback
        return $originalUrl;
    }

    /**
     * Build responsive srcset string if multiple variants exist.
     */
    public function getSrcset(mixed $model): ?string
    {
        if (!$model) {
            return null;
        }

        $type = $this->getTypeForModel($model);
        $id = $this->getIdForModel($model);

        if ($id === null) {
            return null;
        }

        $specs = $this->getVariantSpecs($type);
        $sources = [];

        foreach ($specs as $variant => $spec) {
            $relPath = "images/optimized/{$type}/{$id}_{$variant}.webp";
            if (file_exists(public_path($relPath))) {
                $sources[] = asset($relPath) . ' ' . $spec['width'] . 'w';
            }
        }

        return count($sources) >= 2 ? implode(', ', $sources) : null;
    }

    /**
     * Check if an optimized variant exists on disk.
     */
    public function isOptimized(mixed $model, string $variant = 'thumb'): bool
    {
        if (!$model) {
            return false;
        }

        $type = $this->getTypeForModel($model);
        $id = $this->getIdForModel($model);

        if ($id === null) {
            return false;
        }

        $relPath = "images/optimized/{$type}/{$id}_{$variant}.webp";
        if (file_exists(public_path($relPath))) {
            return true;
        }

        if ($variant === 'thumb') {
            if ($type === 'news' && file_exists(public_path("images/berita-optimized/{$id}.webp"))) {
                return true;
            }
            if ($type === 'events' && file_exists(public_path("images/gallery-optimized/{$id}.webp"))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get image dimensions from manifest or getimagesize.
     */
    public function getDimensions(mixed $model, string $variant = 'thumb'): ?array
    {
        if (!$model) {
            return null;
        }

        $type = $this->getTypeForModel($model);
        $id = $this->getIdForModel($model);
        if ($id === null) {
            return null;
        }

        $manifest = $this->getManifest();
        $key = "{$type}_{$id}_{$variant}";

        if (isset($manifest[$key]['width'], $manifest[$key]['height'])) {
            return [
                'width'  => $manifest[$key]['width'],
                'height' => $manifest[$key]['height'],
            ];
        }

        $fullPath = public_path("images/optimized/{$type}/{$id}_{$variant}.webp");
        if (file_exists($fullPath)) {
            $size = @getimagesize($fullPath);
            if ($size) {
                return ['width' => $size[0], 'height' => $size[1]];
            }
        }

        return null;
    }

    /**
     * Process and optimize an item into responsive WebP derivatives.
     */
    public function optimizeItem(mixed $model, array $options = []): array
    {
        if (!$this->isSupported()) {
            return [
                'status' => 'failed',
                'reason' => 'GD extension or WebP support is unavailable in this environment',
            ];
        }

        $dryRun = !empty($options['dry_run']);
        $force = !empty($options['force']);
        $requestedVariant = $options['variant'] ?? null;

        $type = $this->getTypeForModel($model);
        $id = $this->getIdForModel($model);
        $url = $this->getOriginalUrlForModel($model);

        if (empty($url)) {
            return [
                'status' => 'skipped',
                'reason' => 'Item has no thumbnail URL',
            ];
        }

        // Validate source URL safety
        $validation = $this->validateSourceUrl($url);
        if (!$validation['valid']) {
            return [
                'status' => 'skipped',
                'reason' => 'Untrusted or invalid source URL: ' . $validation['reason'],
            ];
        }

        $specs = $this->getVariantSpecs($type);
        if ($requestedVariant && isset($specs[$requestedVariant])) {
            $specs = [$requestedVariant => $specs[$requestedVariant]];
        }

        // Check if all requested derivatives are already up-to-date
        $sourceHash = md5($url);
        $manifest = $this->getManifest();
        $allReused = true;

        foreach ($specs as $variant => $spec) {
            $destFile = "{$this->baseDir}/{$type}/{$id}_{$variant}.webp";
            $key = "{$type}_{$id}_{$variant}";

            if (!$force && file_exists($destFile)) {
                if (isset($manifest[$key]['source_hash']) && $manifest[$key]['source_hash'] === $sourceHash) {
                    continue;
                }
            }
            $allReused = false;
            break;
        }

        if ($allReused && !$force) {
            return [
                'status'   => 'reused',
                'type'     => $type,
                'id'       => $id,
                'url'      => $url,
                'variants' => array_keys($specs),
            ];
        }

        if ($dryRun) {
            return [
                'status'   => 'processed',
                'dry_run'  => true,
                'type'     => $type,
                'id'       => $id,
                'url'      => $url,
                'variants' => array_keys($specs),
            ];
        }

        // Fetch source image data
        $fetchResult = $this->fetchSourceImage($url);
        if (!$fetchResult['success']) {
            return [
                'status' => 'failed',
                'reason' => 'Failed to fetch source image: ' . $fetchResult['error'],
            ];
        }

        $sourceBinary = $fetchResult['data'];
        $originalSize = strlen($sourceBinary);
        $originalWidth = $fetchResult['width'];
        $originalHeight = $fetchResult['height'];

        // Ensure destination directory exists
        $targetDir = "{$this->baseDir}/{$type}";
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $generatedVariants = [];
        $totalOptimizedSize = 0;

        foreach ($specs as $variant => $spec) {
            $processed = $this->processImage(
                $sourceBinary,
                $spec['width'],
                $spec['quality']
            );

            if (!$processed) {
                return [
                    'status' => 'failed',
                    'reason' => "Failed to process image variant '{$variant}' with GD",
                ];
            }

            $destFile = "{$targetDir}/{$id}_{$variant}.webp";
            $tempFile = "{$destFile}.tmp." . uniqid();

            if (file_put_contents($tempFile, $processed['binary']) === false) {
                @unlink($tempFile);
                return [
                    'status' => 'failed',
                    'reason' => "Failed to write temporary derivative file: {$tempFile}",
                ];
            }

            if (!rename($tempFile, $destFile)) {
                @unlink($tempFile);
                return [
                    'status' => 'failed',
                    'reason' => "Failed to atomically rename derivative file: {$destFile}",
                ];
            }

            $variantSize = strlen($processed['binary']);
            $totalOptimizedSize += $variantSize;

            $key = "{$type}_{$id}_{$variant}";
            $manifest[$key] = [
                'file'         => "images/optimized/{$type}/{$id}_{$variant}.webp",
                'source_url'   => $url,
                'source_hash'  => $sourceHash,
                'variant'      => $variant,
                'width'        => $processed['width'],
                'height'       => $processed['height'],
                'size'         => $variantSize,
                'optimized_at' => date('c'),
            ];

            $generatedVariants[$variant] = [
                'width'  => $processed['width'],
                'height' => $processed['height'],
                'size'   => $variantSize,
            ];
        }

        $this->manifest = $manifest;
        $this->saveManifest();

        return [
            'status'            => 'processed',
            'type'              => $type,
            'id'                => $id,
            'original_size'     => $originalSize,
            'optimized_size'    => $totalOptimizedSize,
            'original_width'    => $originalWidth,
            'original_height'   => $originalHeight,
            'variants'          => $generatedVariants,
        ];
    }

    /**
     * Proportional resize and WebP conversion using GD.
     * Prevents upscaling of smaller images and preserves alpha transparency.
     */
    public function processImage(string $binaryData, int $targetWidth, int $quality = 82): ?array
    {
        if (!$this->isSupported()) {
            return null;
        }

        $srcImage = @imagecreatefromstring($binaryData);
        if (!$srcImage) {
            return null;
        }

        $origW = imagesx($srcImage);
        $origH = imagesy($srcImage);

        if ($origW <= 0 || $origH <= 0) {
            imagedestroy($srcImage);
            return null;
        }

        // Proportional downscale; avoid upscaling
        if ($origW <= $targetWidth) {
            $targetW = $origW;
            $targetH = $origH;
        } else {
            $ratio = $targetWidth / $origW;
            $targetW = (int) round($origW * $ratio);
            $targetH = (int) round($origH * $ratio);
        }

        $dstImage = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
        $transparent = imagecolorallocatealpha($dstImage, 0, 0, 0, 127);
        imagefill($dstImage, 0, 0, $transparent);

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);

        ob_start();
        $ok = imagewebp($dstImage, null, $quality);
        $webpData = ob_get_clean();

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        if (!$ok || empty($webpData)) {
            return null;
        }

        return [
            'binary' => $webpData,
            'width'  => $targetW,
            'height' => $targetH,
        ];
    }

    /**
     * Validate source URL for allowed schemes, trusted hosts, and SSRF prevention.
     */
    public function validateSourceUrl(string $url): array
    {
        // Support local relative paths
        if (str_starts_with($url, '/') || str_starts_with($url, 'images/')) {
            $local = public_path(ltrim($url, '/'));
            if (file_exists($local)) {
                return ['valid' => true, 'is_local' => true];
            }
            return ['valid' => false, 'reason' => 'Local file does not exist'];
        }

        $parsed = parse_url($url);
        if (!$parsed || empty($parsed['scheme']) || empty($parsed['host'])) {
            return ['valid' => false, 'reason' => 'Invalid URL structure'];
        }

        $scheme = strtolower($parsed['scheme']);
        if (!in_array($scheme, ['http', 'https'], true)) {
            return ['valid' => false, 'reason' => "Unsupported scheme '{$scheme}'"];
        }

        $host = strtolower($parsed['host']);

        // Check trusted hosts
        $isTrusted = false;
        foreach ($this->trustedHosts as $trusted) {
            if ($host === $trusted || str_ends_with($host, '.' . $trusted)) {
                $isTrusted = true;
                break;
            }
        }

        if (!$isTrusted) {
            return ['valid' => false, 'reason' => "Host '{$host}' is not in trusted hosts list"];
        }

        // Prevent SSRF: block private and reserved IPs (except localhost testing)
        if ($host !== 'localhost') {
            $ip = filter_var($host, FILTER_VALIDATE_IP) ? $host : @gethostbyname($host);
            if ($ip && $ip !== $host) {
                $isPrivate = !filter_var(
                    $ip,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                );
                if ($isPrivate) {
                    return ['valid' => false, 'reason' => "Host resolves to private IP '{$ip}'"];
                }
            }
        }

        return ['valid' => true, 'is_local' => false];
    }

    /**
     * Safely fetch image from URL or read from local disk.
     */
    public function fetchSourceImage(string $url): array
    {
        // Local asset handling
        if (str_starts_with($url, '/') || str_starts_with($url, 'images/')) {
            $local = public_path(ltrim($url, '/'));
            if (!file_exists($local)) {
                return ['success' => false, 'error' => 'Local file not found'];
            }
            $data = @file_get_contents($local);
            if ($data === false) {
                return ['success' => false, 'error' => 'Failed to read local file'];
            }
            $size = @getimagesizefromstring($data);
            if (!$size) {
                return ['success' => false, 'error' => 'File is not a valid image'];
            }
            return [
                'success' => true,
                'data'    => $data,
                'mime'    => $size['mime'],
                'width'   => $size[0],
                'height'  => $size[1],
            ];
        }

        $encodedUrl = $this->encodeUrlPath($url);

        $ch = curl_init($encodedUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT      => 'JakartaProv-CSIRT-ImageOptimizer/1.0',
        ]);

        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($data === false || !empty($error)) {
            return ['success' => false, 'error' => $error ?: 'cURL request failed'];
        }

        if ($httpCode !== 200) {
            return ['success' => false, 'error' => "HTTP {$httpCode} response"];
        }

        if (strlen($data) > 15 * 1024 * 1024) {
            return ['success' => false, 'error' => 'Image exceeds 15 MB limit'];
        }

        $size = @getimagesizefromstring($data);
        if (!$size) {
            return ['success' => false, 'error' => 'Response is not a valid image format'];
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($size['mime'], $allowedMimes, true)) {
            return ['success' => false, 'error' => "Disallowed MIME type '{$size['mime']}'"];
        }

        return [
            'success' => true,
            'data'    => $data,
            'mime'    => $size['mime'],
            'width'   => $size[0],
            'height'  => $size[1],
        ];
    }

    /**
     * Encode spaces and special characters in URL path.
     */
    public function encodeUrlPath(string $url): string
    {
        $parsed = parse_url($url);
        if (!isset($parsed['path'])) {
            return $url;
        }

        $segments = explode('/', $parsed['path']);
        $encodedSegments = array_map(function ($seg) {
            return rawurlencode(rawurldecode($seg));
        }, $segments);
        $encodedPath = implode('/', $encodedSegments);

        $result = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? '');
        if (isset($parsed['port'])) {
            $result .= ':' . $parsed['port'];
        }
        $result .= $encodedPath;
        if (isset($parsed['query'])) {
            $result .= '?' . $parsed['query'];
        }
        return $result;
    }

    /**
     * Map model or string to category type.
     */
    public function getTypeForModel(mixed $model): string
    {
        if (is_string($model)) {
            return $model;
        }

        if (is_object($model)) {
            if (method_exists($model, 'getPublicationImageCategory')) {
                return $model->getPublicationImageCategory();
            }

            return match (get_class($model)) {
                \App\Models\CybersecurityNews::class => 'news',
                \App\Models\WarningPost::class       => 'warnings',
                \App\Models\Infographic::class       => 'infographics',
                \App\Models\Event::class             => 'events',
                \App\Models\HeroSlide::class         => 'hero',
                default                              => 'misc',
            };
        }

        return 'misc';
    }

    protected function getIdForModel(mixed $model): int|string|null
    {
        if (is_object($model) && isset($model->id)) {
            return $model->id;
        }
        if (is_array($model) && isset($model['id'])) {
            return $model['id'];
        }
        return null;
    }

    protected function getOriginalUrlForModel(mixed $model): ?string
    {
        if (is_string($model)) {
            return $model;
        }
        if (is_object($model)) {
            return $model->thumbnail ?? ($model->gambar ?? null);
        }
        if (is_array($model)) {
            return $model['thumbnail'] ?? ($model['gambar'] ?? null);
        }
        return null;
    }

    /**
     * Read the cache manifest from disk.
     */
    public function getManifest(): array
    {
        if ($this->manifest !== null) {
            return $this->manifest;
        }

        $manifestFile = "{$this->baseDir}/manifest.json";
        if (file_exists($manifestFile)) {
            $data = json_decode(@file_get_contents($manifestFile), true);
            if (is_array($data)) {
                $this->manifest = $data;
                return $this->manifest;
            }
        }

        $this->manifest = [];
        return $this->manifest;
    }

    /**
     * Persist manifest to disk atomically.
     */
    public function saveManifest(): void
    {
        if (!is_dir($this->baseDir)) {
            @mkdir($this->baseDir, 0755, true);
        }

        $manifestFile = "{$this->baseDir}/manifest.json";
        $tempFile = "{$manifestFile}.tmp." . uniqid();

        $json = json_encode($this->manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (file_put_contents($tempFile, $json) !== false) {
            rename($tempFile, $manifestFile);
        }
    }
}
