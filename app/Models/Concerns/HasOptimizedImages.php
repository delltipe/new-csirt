<?php

namespace App\Models\Concerns;

use App\Services\PublicationImageService;

trait HasOptimizedImages
{
    /**
     * Get the publication category key for image optimization.
     */
    public function getPublicationImageCategory(): string
    {
        return match (static::class) {
            \App\Models\CybersecurityNews::class => 'news',
            \App\Models\WarningPost::class       => 'warnings',
            \App\Models\Infographic::class       => 'infographics',
            \App\Models\Event::class             => 'events',
            \App\Models\HeroSlide::class         => 'hero',
            default                              => 'misc',
        };
    }

    /**
     * Get the URL for the optimized thumbnail variant (or graceful original fallback).
     */
    public function getOptimizedThumbnailAttribute(): ?string
    {
        return app(PublicationImageService::class)->getUrl($this, 'thumb');
    }

    /**
     * Get the URL for the optimized detail variant (or graceful original fallback).
     */
    public function getOptimizedDetailAttribute(): ?string
    {
        return app(PublicationImageService::class)->getUrl($this, 'detail');
    }

    /**
     * Get responsive srcset string if multiple variants exist.
     */
    public function getOptimizedSrcsetAttribute(): ?string
    {
        return app(PublicationImageService::class)->getSrcset($this);
    }

    /**
     * Determine if an optimized variant exists on disk.
     */
    public function hasOptimizedImage(string $variant = 'thumb'): bool
    {
        return app(PublicationImageService::class)->isOptimized($this, $variant);
    }

    /**
     * Explicit helper method for specific variant.
     */
    public function optimizedImage(string $variant = 'thumb'): ?string
    {
        return app(PublicationImageService::class)->getUrl($this, $variant);
    }
}
