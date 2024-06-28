<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSubDescription extends Model
{
    use HasFactory;

    public function productDescription(): BelongsTo
    {
        return $this->belongsTo(ProductDescription::class);
    }
}
