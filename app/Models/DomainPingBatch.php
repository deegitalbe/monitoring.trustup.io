<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainPingBatch extends Model
{
    protected $fillable = ['started_at', 'finished_at', 'domain_count'];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function domain_pings()
    {
        return $this->hasMany(DomainPing::class);
    }

    public function count_domain_pinged()
    {
        DomainPing::where('domain_ping_batch_id', $this->id)->count();
    }
}
