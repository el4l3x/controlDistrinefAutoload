<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function widgets() : BelongsToMany {
        return $this->belongsToMany(Report::class, 'partner_report', 'partner_id', 'report_id')
            ->withPivot('total', 'revisados', 'afectados', 'tiempo', 'errores')
            ->wherePivotBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);
    }
}
