<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdditionalService extends Model
{
    use HasFactory;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_additional_service', 'additional_service_id', 'product_id')
            ->withTimestamps();
    }

    public function orderProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product_additional_service', 'additional_service_id', 'order_product_id')
            ->withPivot('del_status')->withTimestamps();
    }
}
