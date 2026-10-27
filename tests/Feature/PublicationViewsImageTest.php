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
        $warning = new WarningPost([
            'title'       => 'Peringatan Uji Coba Optimasi',
            'description' => 'Deskripsi peringatan uji coba',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/sample-warning.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);
        $warning->id = 999;
        $warning->save();

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

    public function test_warning_record_resolves_to_warning_specific_thumbnail_when_derivative_exists(): void
    {
        $warning = new WarningPost([
            'title'       => 'Peringatan Spesifik Thumbnail',
            'description' => 'Deskripsi peringatan spesifik',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/warning-881.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);
        $warning->id = 881;
        $warning->save();

        $optDir = public_path('images/optimized/warnings');
        @mkdir($optDir, 0755, true);
        $thumbFile = "{$optDir}/881_thumb.webp";
        file_put_contents($thumbFile, 'fake-warning-thumb');

        try {
            $response = $this->get(route('warnings.index'));
            $response->assertStatus(200);
            $response->assertSee('images/optimized/warnings/881_thumb.webp');
        } finally {
            @unlink($thumbFile);
        }
    }

    public function test_missing_warning_derivative_falls_back_to_canonical_url_and_never_selects_unrelated_news_image(): void
    {
        $warning = new WarningPost([
            'title'       => 'Peringatan Fallback Canonical',
            'description' => 'Deskripsi fallback canonical',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/canonical-warning-882.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);
        $warning->id = 882;
        $warning->save();

        // Simulate an unrelated news thumbnail existing with matching numeric ID 882
        $newsLegacyDir = public_path('images/berita-optimized');
        @mkdir($newsLegacyDir, 0755, true);
        $newsFile = "{$newsLegacyDir}/882.webp";
        file_put_contents($newsFile, 'fake-unrelated-news-thumb');

        try {
            $response = $this->get(route('warnings.index'));
            $response->assertStatus(200);
            // Must see its own canonical remote image
            $response->assertSee('canonical-warning-882.png');
            // Must NEVER select the unrelated news image based solely on matching numeric ID
            $response->assertDontSee('images/berita-optimized/882.webp');
        } finally {
            @unlink($newsFile);
        }
    }

    public function test_existing_detail_variants_take_precedence_over_fallback_variants(): void
    {
        $event = new Event([
            'title'       => 'Event Precedence Test',
            'description' => 'Deskripsi event precedence',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/gallery/event-883.jpg',
            'event_date'  => now()->addDays(3),
            'location'    => 'Balai Kota',
            'event_type'  => 'seminar',
        ]);
        $event->id = 883;
        $event->save();

        $optDir = public_path('images/optimized/events');
        @mkdir($optDir, 0755, true);
        $detailFile = "{$optDir}/883_detail.webp";
        file_put_contents($detailFile, 'fake-event-detail');

        $galleryDir = public_path('images/gallery-optimized');
        @mkdir($galleryDir, 0755, true);
        $galleryThumb = "{$galleryDir}/883.webp";
        file_put_contents($galleryThumb, 'fake-gallery-thumb');

        try {
            $response = $this->get(route('events.show', $event->id));
            $response->assertStatus(200);
            // Exact detail variant takes precedence
            $response->assertSee('images/optimized/events/883_detail.webp');
            $response->assertDontSee('images/gallery-optimized/883.webp');
        } finally {
            @unlink($detailFile);
            @unlink($galleryThumb);
        }
    }

    public function test_missing_event_detail_variant_uses_existing_gallery_thumbnail(): void
    {
        $event = new Event([
            'title'       => 'Event Fallback Thumbnail Test',
            'description' => 'Deskripsi event fallback',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/gallery/event-884.jpg',
            'event_date'  => now()->addDays(5),
            'location'    => 'Online',
            'event_type'  => 'webinar',
        ]);
        $event->id = 884;
        $event->save();

        $galleryDir = public_path('images/gallery-optimized');
        @mkdir($galleryDir, 0755, true);
        $galleryThumb = "{$galleryDir}/884.webp";
        file_put_contents($galleryThumb, 'fake-gallery-thumb');

        try {
            $response = $this->get(route('events.show', $event->id));
            $response->assertStatus(200);
            // Missing detail variant falls back to gallery thumbnail for events
            $response->assertSee('images/gallery-optimized/884.webp');
        } finally {
            @unlink($galleryThumb);
        }
    }

    public function test_missing_news_and_warning_detail_variants_retain_original_image_fallback(): void
    {
        // 1. News: 600px thumbnail exists, but detail variant does NOT exist
        $news = new CybersecurityNews([
            'title'       => 'News Detail Strict Quality Test',
            'description' => 'Deskripsi news detail quality',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/original-news-885.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);
        $news->id = 885;
        $news->save();

        $newsLegacyDir = public_path('images/berita-optimized');
        @mkdir($newsLegacyDir, 0755, true);
        $newsThumb = "{$newsLegacyDir}/885.webp";
        file_put_contents($newsThumb, 'fake-news-thumb');

        // 2. Warning: thumbnail exists, but detail variant does NOT exist
        $warning = new WarningPost([
            'title'       => 'Warning Detail Strict Quality Test',
            'description' => 'Deskripsi warning detail quality',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/original-warning-886.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);
        $warning->id = 886;
        $warning->save();

        $warnOptDir = public_path('images/optimized/warnings');
        @mkdir($warnOptDir, 0755, true);
        $warnThumb = "{$warnOptDir}/886_thumb.webp";
        file_put_contents($warnThumb, 'fake-warning-thumb');

        try {
            // News detail must NOT upscale thumbnail; retains original fallback
            $respNews = $this->get(route('news.show', $news->id));
            $respNews->assertStatus(200);
            $respNews->assertSee('original-news-885.png');
            $respNews->assertDontSee('images/berita-optimized/885.webp');

            // Warning detail must NOT upscale thumbnail; retains original fallback
            $respWarn = $this->get(route('warnings.show', $warning->id));
            $respWarn->assertStatus(200);
            $respWarn->assertSee('original-warning-886.png');
            $respWarn->assertDontSee('images/optimized/warnings/886_thumb.webp');
        } finally {
            @unlink($newsThumb);
            @unlink($warnThumb);
        }
    }

    public function test_infographics_index_and_show_use_optimized_variants_and_preserve_download(): void
    {
        $info = new Infographic([
            'title'     => 'Infografis Uji Coba Optimasi',
            'thumbnail' => 'https://csirt.jakarta.go.id/images/gallery/sample-infographic.jpg',
        ]);
        $info->id = 999;
        $info->save();

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

    public function test_news_and_warning_detail_views_render_local_derivatives_with_correct_markup_attributes(): void
    {
        $news = new CybersecurityNews([
            'title'       => 'Berita Atribut Markup Valid',
            'description' => 'Deskripsi berita atribut',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/sample-news-attr.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);
        $news->id = 701;
        $news->save();

        $warning = new WarningPost([
            'title'       => 'Peringatan Atribut Markup Valid',
            'description' => 'Deskripsi peringatan atribut',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/sample-warning-attr.png',
            'source'      => 'https://example.com',
            'date'        => now(),
        ]);
        $warning->id = 701;
        $warning->save();

        $newsOptDir = public_path('images/optimized/news');
        $warnOptDir = public_path('images/optimized/warnings');
        @mkdir($newsOptDir, 0755, true);
        @mkdir($warnOptDir, 0755, true);

        $newsDetail = "{$newsOptDir}/701_detail.webp";
        $warnDetail = "{$warnOptDir}/701_detail.webp";
        file_put_contents($newsDetail, 'fake-news-701-detail');
        file_put_contents($warnDetail, 'fake-warn-701-detail');

        try {
            $respNews = $this->get(route('news.show', $news->id));
            $respNews->assertStatus(200);
            $respNews->assertSee('images/optimized/news/701_detail.webp');
            $respNews->assertSee('class="news-detail-content__img"', false);
            $respNews->assertSee('decoding="async"', false);
            $respNews->assertSee('fetchpriority="high"', false);

            $respWarn = $this->get(route('warnings.show', $warning->id));
            $respWarn->assertStatus(200);
            $respWarn->assertSee('images/optimized/warnings/701_detail.webp');
            $respWarn->assertSee('class="warning-detail-content__img"', false);
            $respWarn->assertSee('decoding="async"', false);
            $respWarn->assertSee('fetchpriority="high"', false);
        } finally {
            @unlink($newsDetail);
            @unlink($warnDetail);
        }
    }

    public function test_news_and_warning_detail_category_isolation_for_overlapping_ids(): void
    {
        $news = new CybersecurityNews([
            'title'       => 'Berita Isolasi Kategori',
            'description' => 'Deskripsi berita isolasi',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/news-isolation-702.png',
            'source'      => 'https://example.com/news',
            'date'        => now(),
        ]);
        $news->id = 702;
        $news->save();

        $warning = new WarningPost([
            'title'       => 'Peringatan Isolasi Kategori',
            'description' => 'Deskripsi peringatan isolasi',
            'thumbnail'   => 'https://csirt.jakarta.go.id/images/berita/warning-isolation-702.png',
            'source'      => 'https://example.com/warning',
            'date'        => now(),
        ]);
        $warning->id = 702;
        $warning->save();

        $newsOptDir = public_path('images/optimized/news');
        $warnOptDir = public_path('images/optimized/warnings');
        @mkdir($newsOptDir, 0755, true);
        @mkdir($warnOptDir, 0755, true);

        $newsDetail = "{$newsOptDir}/702_detail.webp";
        $warnDetail = "{$warnOptDir}/702_detail.webp";
        file_put_contents($newsDetail, 'fake-news-detail-content');
        file_put_contents($warnDetail, 'fake-warn-detail-content');

        try {
            // News detail must resolve to news derivative, never warning derivative
            $respNews = $this->get(route('news.show', $news->id));
            $respNews->assertStatus(200);
            $respNews->assertSee('images/optimized/news/702_detail.webp');
            $respNews->assertDontSee('images/optimized/warnings/702_detail.webp');

            // Warning detail must resolve to warning derivative, never news derivative
            $respWarn = $this->get(route('warnings.show', $warning->id));
            $respWarn->assertStatus(200);
            $respWarn->assertSee('images/optimized/warnings/702_detail.webp');
            $respWarn->assertDontSee('images/optimized/news/702_detail.webp');
        } finally {
            @unlink($newsDetail);
            @unlink($warnDetail);
        }
    }
}
