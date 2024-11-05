<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompetitorDivisonled extends Model
{
    use HasFactory;

    protected $table = 'competitors_divisonled';

    public function products() : BelongsToMany {

                return $this->belongsToMany(ProductDivisonled::class, 'competitor_product_divisonled', 'competitor_id', 'product_id')->withPivot('precio', 'url');

    }
}
