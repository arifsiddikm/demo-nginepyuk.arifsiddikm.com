<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Property extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'address', 'city', 'province',
        'latitude', 'longitude', 'price_per_night', 'total_rooms', 'max_guests',
        'thumbnail', 'thumbnail_url', 'image_urls',
        'facilities', 'status', 'rating_avg', 'rating_count',
    ];

    protected $casts = [
        'facilities'      => 'array',
        'image_urls'      => 'array',
        'price_per_night' => 'decimal:2',
        'rating_avg'      => 'float',
    ];

    public function getFacilitiesAttribute($value): array
    {
        if (is_array($value)) return $value;
        if (is_string($value) && !empty($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function getImageUrlsAttribute($value): array
    {
        if (is_array($value)) return $value;
        if (is_string($value) && !empty($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function category()    { return $this->belongsTo(Category::class); }
    public function images()      { return $this->hasMany(PropertyImage::class); }
    public function bookings()    { return $this->hasMany(Booking::class); }
    public function testimonials(){ return $this->hasMany(Testimonial::class)->where('status', 'approved'); }

    public function availableRooms(string $checkin, string $checkout): int
    {
        $booked = $this->bookings()
            ->whereIn('status', ['pending','waiting_payment','paid_unverified','confirmed'])
            ->where('checkin_date', '<', $checkout)
            ->where('checkout_date', '>', $checkin)
            ->sum('rooms');
        return max(0, $this->total_rooms - $booked);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /* Main thumbnail: local > CDN column > first image_urls > placeholder */
    public function getMainImageUrl(): string
    {
        if (!empty($this->attributes['thumbnail'])) {
            return asset('storage/' . $this->attributes['thumbnail']);
        }
        if (!empty($this->attributes['thumbnail_url'])) {
            return $this->attributes['thumbnail_url'];
        }
        $urls = $this->image_urls;
        if (!empty($urls[0])) return $urls[0];
        return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80';
    }

    /* Override accessor so $property->thumbnail_url still works */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->getMainImageUrl();
    }

    /* All images for slider */
    public function getAllImages(): array
    {
        $imgs = [];
        foreach ($this->images as $img) {
            $imgs[] = asset('storage/' . $img->image_path);
        }
        foreach ($this->image_urls as $url) {
            if (!empty($url)) $imgs[] = $url;
        }
        $main = $this->getMainImageUrl();
        if (!in_array($main, $imgs)) array_unshift($imgs, $main);
        return array_values(array_unique($imgs));
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_night, 0, ',', '.');
    }
}
