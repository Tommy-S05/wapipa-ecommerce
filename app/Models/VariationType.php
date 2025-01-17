<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $type
 * @property-read Collection|VariationTypeOption[] $options
 * @property-read int|null $options_count
 */

class VariationType extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'type',
    ];
    public $timestamps = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(VariationTypeOption::class, 'variation_type_id');
    }
}
