<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return BelongsToMany
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(
            Ingredient::class,
            'ingredient_product',
            'product_id',
            'ingredient_id'
        )->withPivot('quantity');
    }
}
