<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
class Banner extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'link',
        'button_text',
        'sort',
        'status',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('desktop_image')->singleFile();
        $this->addMediaCollection('mobile_image')->singleFile();
    }
    
}
