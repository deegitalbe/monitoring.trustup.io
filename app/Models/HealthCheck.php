<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCheck extends Model
{
    protected $fillable = ['finished_at', 'name', 'label', 'notification_message', 'short_summary', 'status', 'meta', 'end_point_id'];

    protected $casts = [
        'finished_at' => 'datetime'
    ];

    public function end_point()
    {
        return $this->belongsTo(EndPoint::class, 'end_point_id');
    }
}
