<?php

namespace App\Console\Commands;

use App\Models\CybersecurityNews;
use App\Models\Event;
use App\Models\Infographic;
use App\Models\WarningPost;
use App\Services\PublicationImageService;
use Illuminate\Console\Command;

class OptimizePublicationImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'publications:optimize-images
                            {--type= : Specific publication type (news, warnings, infographics, events)}
                            {--limit= : Maximum number of items to process across all categories}
                            {--dry-run : Preview items to process without downloading or writing files}
                            {--force : Force re-processing even if up-to-date derivative exists}
                            {--variant= : Only process a specific variant (thumb, detail)}';

    /**
     * The console command description.
     */
    protected $description = 'Process and generate responsive WebP image derivatives for publications';

    public function handle(PublicationImageService $service): int
    {
        if (!$service->isSupported()) {
            $this->error('PHP GD extension or WebP support is not available in this environment.');
            return self::FAILURE;
        }

        $typeFilter = $this->option('type');
        $limit = $this->option('limit') !== null ? (int) $this->option('limit') : null;
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');
        $variant = $this->option('variant');

        $this->info('=== Publication Image Optimization Pipeline ===');
        $this->line('Mode:    ' . ($dryRun ? '<comment>DRY-RUN (Simulated)</comment>' : '<info>LIVE</info>'));
        $this->line('Filter:  ' . ($typeFilter ?: 'all (news, warnings, infographics, events)'));
        if ($limit !== null) {
            $this->line('Limit:   ' . $limit . ' items');
        }
        if ($force) {
            $this->line('Force:   <comment>YES (overwriting existing derivatives)</comment>');
        }
        $this->newLine();

        $models = $this->gatherItems($typeFilter);
        $totalDiscovered = count($models);

        if ($limit !== null && $limit > 0) {
            $models = array_slice($models, 0, $limit);
        }

        $counts = [
            'discovered' => $totalDiscovered,
            'targeted'   => count($models),
            'processed'  => 0,
            'reused'     => 0,
            'skipped'    => 0,
            'failed'     => 0,
        ];

        $totalOriginalBytes = 0;
        $totalOptimizedBytes = 0;

        $rows = [];

        foreach ($models as $item) {
            $type = $service->getTypeForModel($item);
            $id = $item->id ?? 'unknown';
            $url = $item->thumbnail ?? ($item->gambar ?? '');

            $result = $service->optimizeItem($item, [
                'dry_run' => $dryRun,
                'force'   => $force,
                'variant' => $variant,
            ]);

            $status = $result['status'] ?? 'unknown';

            switch ($status) {
                case 'processed':
                    $counts['processed']++;
                    if (!empty($result['original_size']) && !empty($result['optimized_size'])) {
                        $totalOriginalBytes += $result['original_size'];
                        $totalOptimizedBytes += $result['optimized_size'];
                        $savings = 100 - (($result['optimized_size'] / $result['original_size']) * 100);
                        $detail = sprintf(
                            '%0.1f KB -> %0.1f KB (%0.1f%% savings)',
                            $result['original_size'] / 1024,
                            $result['optimized_size'] / 1024,
                            $savings
                        );
                    } else {
                        $detail = $dryRun ? 'Ready to process' : 'Generated';
                    }
                    $statusFormatted = '<info>PROCESSED</info>';
                    break;

                case 'reused':
                    $counts['reused']++;
                    $detail = 'Up-to-date derivative exists';
                    $statusFormatted = '<comment>REUSED</comment>';
                    break;

                case 'skipped':
                    $counts['skipped']++;
                    $detail = $result['reason'] ?? 'Skipped';
                    $statusFormatted = '<comment>SKIPPED</comment>';
                    break;

                case 'failed':
                default:
                    $counts['failed']++;
                    $detail = $result['reason'] ?? 'Unknown error';
                    $statusFormatted = '<error>FAILED</error>';
                    break;
            }

            $rows[] = [
                $type,
                $id,
                $this->truncate($item->title ?? 'Untitled', 32),
                $statusFormatted,
                $detail,
            ];
        }

        $this->table(['Type', 'ID', 'Title', 'Status', 'Details'], $rows);

        $this->newLine();
        $this->info('=== Summary Report ===');
        $this->line("Discovered:  {$counts['discovered']} total items");
        $this->line("Targeted:    {$counts['targeted']} items");
        $this->line("Processed:   <info>{$counts['processed']}</info>");
        $this->line("Reused:      <comment>{$counts['reused']}</comment>");
        $this->line("Skipped:     <comment>{$counts['skipped']}</comment>");
        $this->line("Failed:      " . ($counts['failed'] > 0 ? "<error>{$counts['failed']}</error>" : "0"));

        if ($totalOriginalBytes > 0 && $totalOptimizedBytes > 0) {
            $totalSavings = 100 - (($totalOptimizedBytes / $totalOriginalBytes) * 100);
            $this->newLine();
            $this->info(sprintf(
                'Data reduction: %0.2f MB -> %0.2f MB (%0.1f%% reduction, saved %0.2f MB)',
                $totalOriginalBytes / (1024 * 1024),
                $totalOptimizedBytes / (1024 * 1024),
                $totalSavings,
                ($totalOriginalBytes - $totalOptimizedBytes) / (1024 * 1024)
            ));
        }

        return $counts['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Gather publication items based on optional type filter.
     */
    protected function gatherItems(?string $typeFilter): array
    {
        $items = [];

        $typeMap = [
            'news'         => CybersecurityNews::class,
            'warnings'     => WarningPost::class,
            'infographics' => Infographic::class,
            'events'       => Event::class,
        ];

        if ($typeFilter) {
            $types = array_filter(array_map('trim', explode(',', strtolower($typeFilter))));
        } else {
            $types = array_keys($typeMap);
        }

        foreach ($types as $type) {
            if (isset($typeMap[$type])) {
                $class = $typeMap[$type];
                foreach ($class::orderBy('id', 'asc')->get() as $item) {
                    $items[] = $item;
                }
            }
        }

        return $items;
    }

    protected function truncate(string $text, int $max = 30): string
    {
        return mb_strlen($text) > $max ? mb_substr($text, 0, $max - 3) . '...' : $text;
    }
}
