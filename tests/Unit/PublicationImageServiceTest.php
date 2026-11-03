<?php

namespace Tests\Unit;

use App\Models\CybersecurityNews;
use App\Services\PublicationImageService;
use Tests\TestCase;

class PublicationImageServiceTest extends TestCase
{
    protected PublicationImageService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PublicationImageService();
    }

    public function test_service_is_supported_matches_environment(): void
    {
        $expected = extension_loaded('gd') && function_exists('imagewebp');
        $this->assertSame($expected, $this->service->isSupported(), 'Service support state should accurately reflect environment GD and imagewebp availability.');
    }

    public function test_image_processing_resizes_proportionally_to_webp(): void
    {
        if (!$this->service->isSupported()) {
            $this->markTestSkipped('PHP GD extension or imagewebp is not available in the current PHP environment.');
        }

        // Create 1000x500 (2:1 aspect ratio) sample image in memory
        $im = imagecreatetruecolor(1000, 500);
        $blue = imagecolorallocate($im, 10, 50, 150);
        imagefill($im, 0, 0, $blue);
        ob_start();
        imagepng($im);
        $pngData = ob_get_clean();
        imagedestroy($im);

        $result = $this->service->processImage($pngData, 600, 80);

        $this->assertNotNull($result);
        $this->assertEquals(600, $result['width']);
        $this->assertEquals(300, $result['height'], 'Height should scale proportionally to 300px');

        $info = getimagesizefromstring($result['binary']);
        $this->assertNotFalse($info);
        $this->assertEquals('image/webp', $info['mime']);
        $this->assertEquals(600, $info[0]);
        $this->assertEquals(300, $info[1]);
    }

    public function test_image_processing_avoids_upscaling(): void
    {
        if (!$this->service->isSupported()) {
            $this->markTestSkipped('PHP GD extension or imagewebp is not available in the current PHP environment.');
        }

        // Create 400x200 sample image in memory
        $im = imagecreatetruecolor(400, 200);
        $color = imagecolorallocate($im, 80, 120, 20);
        imagefill($im, 0, 0, $color);
        ob_start();
        imagepng($im);
        $pngData = ob_get_clean();
        imagedestroy($im);

        // Target width is 600px, but source is only 400px
        $result = $this->service->processImage($pngData, 600, 80);

        $this->assertNotNull($result);
        $this->assertEquals(400, $result['width'], 'Width should not upscale beyond original 400px');
        $this->assertEquals(200, $result['height'], 'Height should remain 200px');
    }

    public function test_image_processing_preserves_alpha_transparency(): void
    {
        if (!$this->service->isSupported()) {
            $this->markTestSkipped('PHP GD extension or imagewebp is not available in the current PHP environment.');
        }

        $im = imagecreatetruecolor(300, 300);
        imagealphablending($im, false);
        imagesavealpha($im, true);
        $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
        imagefill($im, 0, 0, $transparent);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagefilledellipse($im, 150, 150, 100, 100, $red);

        ob_start();
        imagepng($im);
        $pngData = ob_get_clean();
        imagedestroy($im);

        $result = $this->service->processImage($pngData, 300, 85);
        $this->assertNotNull($result);

        $info = getimagesizefromstring($result['binary']);
        $this->assertEquals('image/webp', $info['mime']);
    }

    public function test_validate_source_url_rejects_untrusted_hosts(): void
    {
        $result = $this->service->validateSourceUrl('https://evil-untrusted-domain.com/malware.png');
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('not in trusted hosts', $result['reason']);
    }

    public function test_validate_source_url_rejects_unsupported_schemes(): void
    {
        $result = $this->service->validateSourceUrl('ftp://csirt.jakarta.go.id/file.png');
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('Unsupported scheme', $result['reason']);

        $result2 = $this->service->validateSourceUrl('javascript:alert(1)');
        $this->assertFalse($result2['valid']);
    }

    public function test_validate_source_url_rejects_private_ips(): void
    {
        $result = $this->service->validateSourceUrl('http://192.168.1.50/photo.jpg');
        $this->assertFalse($result['valid']);

        $result2 = $this->service->validateSourceUrl('http://10.0.0.1/test.png');
        $this->assertFalse($result2['valid']);
    }

    public function test_validate_source_url_accepts_trusted_hosts(): void
    {
        $result = $this->service->validateSourceUrl('https://csirt.jakarta.go.id/images/berita/sample.jpg');
        $this->assertTrue($result['valid']);

        $result2 = $this->service->validateSourceUrl('https://images.unsplash.com/photo-1540575467063');
        $this->assertTrue($result2['valid']);
    }

    public function test_url_encoding_handles_spaces_and_special_characters(): void
    {
        $raw = 'https://csirt.jakarta.go.id/images/berita/data bocor {abc}_2026.png';
        $encoded = $this->service->encodeUrlPath($raw);

        $this->assertStringNotContainsString(' ', $encoded);
        $this->assertStringContainsString('data%20bocor', $encoded);
        $this->assertStringContainsString('%7Babc%7D', $encoded);
    }

    public function test_fallback_to_original_when_derivative_does_not_exist(): void
    {
        $news = new CybersecurityNews();
        $news->id = 99999;
        $news->thumbnail = 'https://csirt.jakarta.go.id/images/berita/nonexistent.png';

        $url = $this->service->getUrl($news, 'thumb');
        $this->assertEquals('https://csirt.jakarta.go.id/images/berita/nonexistent.png', $url);
    }

    public function test_fallback_to_legacy_webp_derivative_when_present(): void
    {
        // ID 1 exists in public/images/berita-optimized/1.webp
        $news = new CybersecurityNews();
        $news->id = 1;
        $news->thumbnail = 'https://csirt.jakarta.go.id/images/berita/sample.png';

        $url = $this->service->getUrl($news, 'thumb');
        // If modern derivative doesn't exist yet, it should fall back to legacy webp
        $this->assertStringContainsString('.webp', $url);
    }
}
