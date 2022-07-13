<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainPing extends Model
{

    protected $fillable = ['url', 'status', 'answer_time_ms', 'domain_ping_batch_id', 'dns_a'];

    protected $casts = [
        'batch_datetime' => 'datetime'
    ];

}
