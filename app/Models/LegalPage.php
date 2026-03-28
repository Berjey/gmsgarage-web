<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LegalPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_active',
        'is_required_in_forms',
        'is_optional_in_forms',
        'version',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_required_in_forms' => 'boolean',
        'is_optional_in_forms' => 'boolean',
    ];

    /**
     * Get active legal pages (Automatically displayed in footer)
     */
    public static function getActive()
    {
        return Cache::remember('legal_pages_active', 3600, function () {
            return self::where('is_active', true)->orderBy('title')->get();
        });
    }

    /**
     * Get footer pages - Basit: Aktif olan tüm sayfalar footer'da görünür
     */
    public static function getFooterPages()
    {
        return Cache::remember('legal_pages_footer', 3600, function () {
            return self::where('is_active', true)->orderBy('title')->get();
        });
    }

    /**
     * Get pages required in forms (checkbox olarak gösterilecekler)
     */
    public static function getFormPages()
    {
        return Cache::remember('legal_pages_form', 3600, function () {
            return self::where('is_active', true)
                ->where('is_required_in_forms', true)
                ->orderBy('title')
                ->get();
        });
    }

    /**
     * Get page by slug
     */
    public static function getBySlug($slug)
    {
        return self::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    /**
     * Increment version when content is updated
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($page) {
            if ($page->isDirty('content')) {
                $page->version++;
            }
        });
    }
}
