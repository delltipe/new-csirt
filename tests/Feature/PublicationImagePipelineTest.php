<?php

namespace Tests\Feature;

use App\Models\CybersecurityNews;
use App\Models\Event;
use App\Models\Infographic;
use App\Models\WarningPost;
use App\Services\PublicationImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationImagePipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_dry_run_discovers_publication_items(): void
    {
        // Seed some sample records
        CybersecurityNews::create([
            'title'       => 'Test News Article',
            'description' => 'Test description',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/sample.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);

        $this->artisan('publications:optimize-images', ['--dry-run' => true])
            ->assertExitCode(0)
            ->expectsOutputToContain('DRY-RUN (Simulated)')
            ->expectsOutputToContain('PROCESSED');
    }

    public function test_command_respects_type_filter_and_limit(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            CybersecurityNews::create([
                'title'       => "News Item {$i}",
                'description' => 'Desc',
                'thumbnail'   => "https://csirt.jakarta.go.id/images/berita/{$i}.png",
                'source'      => 'https://example.com',
                'date'        => now(),
            ]);
        }

        $this->artisan('publications:optimize-images', [
            '--dry-run' => true,
            '--type'    => 'news',
            '--limit'   => 2,
        ])
            ->assertExitCode(0)
            ->expectsOutputToContain('Limit:   2 items')
            ->expectsOutputToContain('Targeted:    2 items');
    }

    public function test_model_trait_provides_accessors_and_fallbacks(): void
    {
        $news = CybersecurityNews::create([
            'title'       => 'News With Fallback',
            'description' => 'Testing fallback image',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/original.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);

        $this->assertEquals('news', $news->getPublicationImageCategory());
        // For fresh id (e.g. 1 in fresh DB or higher), if no derivative exists it returns original
        $thumb = $news->optimized_thumbnail;
        $this->assertNotEmpty($thumb);

        $detail = $news->optimized_detail;
        $this->assertNotEmpty($detail);
    }

    public function test_safe_handling_of_untrusted_sources(): void
    {
        $service = app(PublicationImageService::class);
        $result = $service->validateSourceUrl('https://evil.untrusted.com/image.png');

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('not in trusted hosts', $result['reason']);
    }

    public function test_idempotent_processing_reuses_existing_derivatives(): void
    {
        $service = app(PublicationImageService::class);

        // Create an in-memory item
        $item = new CybersecurityNews();
        $item->id = 8888;
        $item->thumbnail = 'https://csirt.jakarta.go.id/images/berita/reused-test.png';

        // Fake derivative on disk
        $testDir = public_path('images/optimized/news');
        if (!is_dir($testDir)) {
            @mkdir($testDir, 0755, true);
        }
        $destThumb = "{$testDir}/8888_thumb.webp";
        $destDetail = "{$testDir}/8888_detail.webp";
        file_put_contents($destThumb, 'fake-webp-thumb');
        file_put_contents($destDetail, 'fake-webp-detail');

        // Update manifest
        $manifest = $service->getManifest();
        $hash = md5($item->thumbnail);
        $manifest['news_8888_thumb'] = [
            'file' => 'images/optimized/news/8888_thumb.webp',
            'source_hash' => $hash,
        ];
        $manifest['news_8888_detail'] = [
            'file' => 'images/optimized/news/8888_detail.webp',
            'source_hash' => $hash,
        ];

        // Save manifest using reflection to set protected property
        $ref = new \ReflectionProperty($service, 'manifest');
        $ref->setValue($service, $manifest);
        $service->saveManifest();

        $result = $service->optimizeItem($item);

        // Cleanup fake files
        @unlink($destThumb);
        @unlink($destDetail);

        $this->assertEquals('reused', $result['status']);
    }
}
