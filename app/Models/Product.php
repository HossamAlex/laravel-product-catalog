<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Sluggable;
    use SoftDeletes;
    use HasFactory;
    

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'separator' => '-',
            ],
        ];
    }
    
    protected $fillable = [
        'title',
        'slug',
        'description',
        'brand_id',
        'price',
        'special_price',
        'sort',
        'sku',
        'is_featured',
        'is_trending',
        'is_recommended',
        'published_at',
        'views_count',
        'stock',
    ];
    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:3',
        'special_price' => 'decimal:3',
        'published_at' => 'datetime',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function generateSku(): void
    {
        if ($this->sku) {
            return;
        }

        $this->updateQuietly([
            'sku' => 'VN-' . str_pad($this->id, 6, '0', STR_PAD_LEFT),
        ]);
    }


     public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')->singleFile();
        $this->addMediaCollection('gallery');
        $this->addMediaCollection('videos');

    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }

    
}
