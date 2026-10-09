<?php

namespace Tests\Feature;

use App\Models\CybersecurityNews;
use App\Models\Event;
use App\Models\Infographic;
use App\Models\WarningPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationViewsImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_and_show_use_optimized_images_when_available(): void
    {
        $news = new CybersecurityNews([
            'title'       => 'Berita Uji Coba Optimasi',
            'description' => 'Deskripsi berita uji coba',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/sample-news.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);
        $news->id = 999;
        $news->save();

        // 1. Without derivative: falls back to canonical original
        $response = $this->get(route('news.index'));
        $response->assertStatus(200);
        $response->assertSee('sample-news.png');

        $responseDetail = $this->get(route('news.show', $news->id));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('sample-news.png');

        // 2. With derivative created
        $optDir = public_path('images/optimized/news');
        @mkdir($optDir, 0755, true);
        $thumbFile = "{$optDir}/{$news->id}_thumb.webp";
        $detailFile = "{$optDir}/{$news->id}_detail.webp";
        file_put_contents($thumbFile, 'fake-thumb-webp');
        file_put_contents($detailFile, 'fake-detail-webp');

        try {
            $response = $this->get(route('news.index'));
            $response->assertStatus(200);
            $response->assertSee("images/optimized/news/{$news->id}_thumb.webp");

            $responseDetail = $this->get(route('news.show', $news->id));
            $responseDetail->assertStatus(200);
            $responseDetail->assertSee("images/optimized/news/{$news->id}_detail.webp");
        } finally {
            @unlink($thumbFile);
            @unlink($detailFile);
        }
    }

    public function test_warnings_index_and_show_use_optimized_images_when_available(): void
    {
        $warning = WarningPost::create([
            'title'       => 'Peringatan Uji Coba Optimasi',
            'description' => 'Deskripsi peringatan uji coba',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/sample-warning.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);

        // Fallback check
        $response = $this->get(route('warnings.index'));
        $response->assertStatus(200);
        $response->assertSee('sample-warning.png');

        // With derivative
        $optDir = public_path('images/optimized/warnings');
        @mkdir($optDir, 0755, true);
        $thumbFile = "{$optDir}/{$warning->id}_thumb.webp";
        $detailFile = "{$optDir}/{$warning->id}_detail.webp";
        file_put_contents($thumbFile, 'fake-thumb-webp');
        file_put_contents($detailFile, 'fake-detail-webp');

        try {
            $response = $this->get(route('warnings.index'));
            $response->assertStatus(200);
            $response->assertSee("images/optimized/warnings/{$warning->id}_thumb.webp");

            $responseDetail = $this->get(route('warnings.show', $warning->id));
            $responseDetail->assertStatus(200);
            $responseDetail->assertSee("images/optimized/warnings/{$warning->id}_detail.webp");
        } finally {
            @unlink($thumbFile);
            @unlink($detailFile);
        }
    }

    public function test_infographics_index_and_show_use_optimized_variants_and_preserve_download(): void
    {
        $info = Infographic::create([
            'title'     => 'Infografis Uji Coba Optimasi',
            'thumbnail' => 'https://csirt.jakarta.go.id/images/gallery/sample-infographic.jpg',
        ]);

        // Without derivative: fallback to original
        $response = $this->get(route('infographics.index'));
        $response->assertStatus(200);
        $response->assertSee('sample-infographic.jpg');

        // With derivatives
        $optDir = public_path('images/optimized/infographics');
        @mkdir($optDir, 0755, true);
        $thumbFile = "{$optDir}/{$info->id}_thumb.webp";
        $detailFile = "{$optDir}/{$info->id}_detail.webp";
        file_put_contents($thumbFile, 'fake-thumb-webp');
        file_put_contents($detailFile, 'fake-detail-webp');

        try {
            $response = $this->get(route('infographics.index'));
            $response->assertStatus(200);
            // Lightbox data-img should use optimized detail
            $response->assertSee("images/optimized/infographics/{$info->id}_detail.webp");
            // Grid card img src should use optimized thumbnail
            $response->assertSee("images/optimized/infographics/{$info->id}_thumb.webp");

            $responseDetail = $this->get(route('infographics.show', $info->id));
            $responseDetail->assertStatus(200);
            $responseDetail->assertSee("images/optimized/infographics/{$info->id}_detail.webp");
            // Unduh download button should still reference the original canonical asset
            $responseDetail->assertSee('href="https://csirt.jakarta.go.id/images/gallery/sample-infographic.jpg"', false);
        } finally {
            @unlink($thumbFile);
            @unlink($detailFile);
        }
    }

    public function test_events_index_and_show_use_optimized_images_when_available(): void
    {
        $event = new Event([
            'title'       => 'Event Uji Coba Optimasi',
            'description' => 'Deskripsi event uji coba',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/gallery/sample-event.jpg',
            'event_date'  => now()->addDays(2),
            'location'    => 'Balai Kota',
            'event_type'  => 'webinar',
        ]);
        $event->id = 999;
        $event->save();

        // Without derivative
        $response = $this->get(route('events.index'));
        $response->assertStatus(200);
        $response->assertSee('sample-event.jpg');

        // With derivative
        $optDir = public_path('images/optimized/events');
        @mkdir($optDir, 0755, true);
        $thumbFile = "{$optDir}/{$event->id}_thumb.webp";
        $detailFile = "{$optDir}/{$event->id}_detail.webp";
        file_put_contents($thumbFile, 'fake-thumb-webp');
        file_put_contents($detailFile, 'fake-detail-webp');

        try {
            $response = $this->get(route('events.index'));
            $response->assertStatus(200);
            $response->assertSee("images/optimized/events/{$event->id}_thumb.webp");

            $responseDetail = $this->get(route('events.show', $event->id));
            $responseDetail->assertStatus(200);
            $responseDetail->assertSee("images/optimized/events/{$event->id}_detail.webp");
        } finally {
            @unlink($thumbFile);
            @unlink($detailFile);
        }
    }
}
