<?php

namespace App\Console\Commands;

use App\Jobs\HealthCheckEndPointJob;
use App\Models\EndPoint;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class HealthCheckEndPointCommand extends Command
{
    protected $signature = 'health:check-endpoints';

    protected $description = 'Create jobs that Health Check all monitored end points';

    public function handle()
    {
        foreach (EndPoint::where('is_monitored', true)->get() as $endPoint) {
            HealthCheckEndPointJob::dispatch($endPoint)->onQueue('priority-1');
        }
    }
}
