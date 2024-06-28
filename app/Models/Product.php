<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function ratings(): HasMany
    {
        return $this->hasMany(ProductRating::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function productStandard(): BelongsTo
    {
        return $this->belongsTo(ProductStandard::class);
    }

    public function woodType(): BelongsTo
    {
        return $this->belongsTo(WoodType::class);
    }

    public function productTypes(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function baskets(): BelongsToMany
    {
        return $this->belongsToMany(
            Basket::class,
            'basket_product',
            'product_id',
            'basket_id'
        )->withPivot('quantity')->withPivot('with_cubic_meters')->withTimestamps();
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            Order::class,
            'order_product',
            'product_id',
            'order_id'
        )->withPivot('quantity')->withTimestamps();
    }


    public function additionalServices(): BelongsToMany
    {
        return $this->belongsToMany(
            AdditionalService::class,
            'product_additional_service',
            'product_id',
            'additional_service_id'
        )->withTimestamps();
    }

    public function orderAdditionalServices(): BelongsToMany
    {
        return $this->belongsToMany(
            AdditionalService::class,
            'order_product_additional_service',
            'order_product_id',
            'additional_service_id'
        )->withPivot('del_status')->withTimestamps();
    }

    public function productDescription(): HasOne
    {
        return $this->hasOne(ProductDescription::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }
}
