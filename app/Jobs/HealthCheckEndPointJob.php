<?php

namespace App\Jobs;

use App\Models\EndPoint;
use App\Models\HealthCheck;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class HealthCheckEndPointJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $endPoint;

    public function __construct(EndPoint $endPoint)
    {
        $this->endPoint = $endPoint;
    }

    public function do_ping($finished_at)
    {
        try {
            $startTime = microtime(true);
            $ping_response = Http::withoutVerifying()
                ->timeout(20)
                ->withHeaders(['X-Server-Authorization' => env('TRUSTUP_SERVER_AUTHORIZATION')])
                ->get($this->endPoint->url . ($this->endPoint->ping_default_url ? '/trustup-io/health/ping' : ''));

            $endTime = microtime(true);
            $ping_time_ms = (round(($endTime - $startTime), 3) * 1000);

            HealthCheck::create([
                'finished_at' => $finished_at,
                'name' => 'Ping',
                'label' => 'Ping',
                'notification_message' => 'Ping',
                'short_summary' => $ping_response->status().' - '.$ping_time_ms.'ms',
                'status' => $ping_response->status() == 200 ? 'ok' : 'failed',
                'meta' => null,
                'end_point_id' => $this->endPoint->id
            ]);
        } catch (\Exception $e) {
            $endTime = microtime(true);
            $ping_time_ms = (round(($endTime - $startTime), 3) * 1000);

            HealthCheck::create([
                'finished_at' => $finished_at,
                'name' => 'Ping',
                'label' => 'Ping',
                'notification_message' => 'Ping',
                'short_summary' => '0 - '.$ping_time_ms.'ms',
                'status' => 'failed',
                'meta' => null,
                'end_point_id' => $this->endPoint->id
            ]);
        }
    }

    public function handle()
    {
        $health_checks = $this->endPoint->getHealthChecks();
        $finished_at = Carbon::now();

        $this->do_ping($finished_at);

        if (!$health_checks) {
            HealthCheck::create([
                'finished_at' => $finished_at,
                'name' => 'HealthCheckFailed',
                'label' => 'Health Check Failed',
                'notification_message' => 'Cannot get health checks from end point',
                'short_summary' => 'Cannot get health checks from end point',
                'status' => -1,
                'meta' => '{}',
                'end_point_id' => $this->endPoint->id
            ]);
        }

        else {
            foreach ($health_checks->checkResults as $check) {

                HealthCheck::create([
                    'finished_at' => $finished_at,
                    'name' => $check->name,
                    'label' => $check->label,
                    'notification_message' => $check->notificationMessage,
                    'short_summary' => $check->shortSummary,
                    'status' => $check->status,
                    'meta' => json_encode($check->meta),
                    'end_point_id' => $this->endPoint->id
                ]);
            }
        }

        $this->endPoint->last_health_check_timestamp = $finished_at;
        $this->endPoint->save();

    }
}
