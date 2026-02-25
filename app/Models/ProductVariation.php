<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @mixin Builder
 * @property int $id
 * @property int $product_id
 * @property array $variation_type_option_ids
 * @property float $price
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 */
class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'variation_type_option_ids',
        'price',
        'quantity',
    ];

    protected $casts = [
        'variation_type_option_ids' => 'json',
    ];

//    protected static function boot(): void
//    {
//        parent::boot();
//
//        static::saving(function ($productVariation) {
//            // Verificar que los IDs de opciones de variación existan
//            foreach ($productVariation->variation_type_option_ids as $optionId) {
//                if (!VariationTypeOption::find($optionId)) {
//                    throw new \Exception("Invalid variation type option ID: {$optionId}");
//                }
//            }
//        });
//    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
