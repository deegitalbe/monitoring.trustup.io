<?php

namespace App\Console\Commands;

use Acamposm\Ping\Ping;
use Acamposm\Ping\PingCommandBuilder;
use App\Jobs\HealthCheckDomainJob;
use App\Models\DomainPing;
use App\Models\DomainPingBatch;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;

class HealthCheckDomainsCommand extends Command
{
    protected $signature = 'health:check-domains';

    protected $description = 'Command description';

    public function handle()
    {
        $domains_reponse = Http::acceptJson()->withHeaders(['Authorization' => 'Bearer ' . env('BEARER_TOKEN_TEST')])->get('https://website.trustup.io/common-api/domains');
        $data = $domains_reponse->json()['data'];

        $domain_ping_batch = DomainPingBatch::create([
            'started_at' => Carbon::now(),
            'domain_count' => count($data),
        ]);

        $domainJobs = [];

        foreach ($data as $index => $domain) {
            $this->info('Job created: Ping domain ' . ($index + 1) . '/' . $domain_ping_batch->domain_count);
            array_push($domainJobs, new HealthCheckDomainJob($domain_ping_batch->id, $domain));
        }

        $batch = Bus::batch($domainJobs)
            ->then(function (Batch $batch) {

            })->catch(function (Batch $batch, \Throwable $e) {
                $domain_ping_batch = DomainPingBatch::where('job_batches_id', $batch->id)->first();
                $domain_ping_batch->failed = 1;

                if (!$domain_ping_batch->failed_reason)
                    $domain_ping_batch->failed_reason = $e->getMessage();
                else
                    $domain_ping_batch->failed_reason .= $e->getMessage();

                $domain_ping_batch->save();
            })->finally(function (Batch $batch) {
                $domain_ping_batch = DomainPingBatch::where('job_batches_id', $batch->id)->first();
                $domain_ping_batch->finished_at = Carbon::now();
                $domain_ping_batch->save();
            })->allowFailures()->dispatch();

        // Save the created job batch to retreive the domain_batch later
        $domain_ping_batch->job_batches_id = $batch->id;
        $domain_ping_batch->save();
    }
}
