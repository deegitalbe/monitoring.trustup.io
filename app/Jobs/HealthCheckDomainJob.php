<?php

namespace App\Jobs;

use App\Models\DomainPing;
use App\Models\DomainPingBatch;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Spatie\Dns\Dns;

class HealthCheckDomainJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $domain_ping_batch_id;
    public $domain;

    public function __construct($domain_ping_batch_id, $domain)
    {
        $this->domain_ping_batch_id = $domain_ping_batch_id;
        $this->domain = $domain;
    }

    public function handle()
    {
        $startTime = microtime(true);

        try {
            $dns = new Dns();
            $result = $dns->getRecords($this->domain['url'], 'A');

            $customer_domain_response = Http::withoutVerifying()->get($this->domain['url']);
            $endTime = microtime(true);

            DomainPing::create([
                'url' => $this->domain['url'],
                'status' => $customer_domain_response->status(),
                'answer_time_ms' => (round(($endTime - $startTime), 3) * 1000), // answer time in ms
                'domain_ping_batch_id' => $this->domain_ping_batch_id,
                'dns_a' => count($result) > 0 ? $result[0]->ip() : null,
            ]);
        } catch (ConnectionException $e) {
            $endTime = microtime(true);

            DomainPing::create([
                'url' => $this->domain['url'],
                'status' => -1,
                'answer_time_ms' => (round(($endTime - $startTime), 3) * 1000), // answer time in ms
                'domain_ping_batch_id' => $this->domain_ping_batch_id,
                'dns_a' => count($result) > 0 ? $result[0]->ip() : null,
            ]);
        }
    }
}
