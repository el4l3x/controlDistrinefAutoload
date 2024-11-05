<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductDivisonled extends Model
{
    use HasFactory;

    protected $table = 'products_divisonled';

    public function competidor() : BelongsToMany {

                return $this->belongsToMany(CompetitorDivisonled::class, 'competitor_product_divisonled', 'product_id', 'competitor_id')->withPivot('precio', 'url', 'id');

    }
}