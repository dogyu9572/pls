<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyVisitorStat extends Model
{
    protected $fillable = [
        'visit_date',
        'visitor_count',
        'page_views',
        'unique_visitors',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'visitor_count' => 'integer',
        'page_views' => 'integer',
        'unique_visitors' => 'integer',
    ];
}
