<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property int $variation_type_id
 * @property string $name
 */

class VariationTypeOption extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'variation_type_id',
        'name',
        'slug',
    ];
    public $timestamps = false;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100);

        $this->addMediaConversion('preview')
            ->width(480);

        $this->addMediaConversion('large')
            ->width(1200);
    }

    public function variationType(): BelongsTo
    {
        return $this->belongsTo(VariationType::class);
    }
}
