<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    public function additionalServices(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalService::class, 'order_product_additional_service', 'order_product_id', 'additional_service_id')
            ->withPivot('del_status')->withTimestamps();
    }
}
