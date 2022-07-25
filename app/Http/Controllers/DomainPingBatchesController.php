<?php

namespace App\Http\Controllers;

use App\Models\DomainPing;
use App\Models\DomainPingBatch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainPingBatchesController extends Controller
{
    public function index()
    {
        $domain_ping_batches = DomainPingBatch::withCount([
            'domain_pings',
            'domain_pings as domain_pings_failed_count' => function (Builder $query) {
                $query->where('status', '!=', 200);
            },
            'domain_pings as domain_pings_success_count' => function (Builder $query) {
                $query->where('status', 200);
            },
        ])
            ->orderBy('started_at', 'desc')
            ->get();

        return view('domain_pings_batches.index', [
            'domain_ping_batches' => $domain_ping_batches
        ]);
    }

    public function domain_stat()
    {
        if (!request()->url)
            return redirect(route('domain-pings-batches.index'));

        $datas = DB::table('domain_pings')
            ->select(['answer_time_ms', 'created_at'])
            ->where('url', request()->url)
            ->orderBy('created_at')
            ->get();

        return view('domain_pings_batches.domain_stats', [
            'url' => request()->url,
            'datas' => $datas
        ]);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(DomainPingBatch $domain_pings_batch)
    {
        $domain_pings_batch->load('domain_pings');


        $running_domain_ping_batch = DomainPingBatch::where('finished_at',  null)
            ->where('started_at', '>', $domain_pings_batch->started_at)
            ->get()
            ->last();

        return view('domain_pings_batches.show', [
            'domain_pings_batch' => $domain_pings_batch,
            'running_domain_ping_batch' => $running_domain_ping_batch ?: null,
        ]);
    }

    public function edit(DomainPingBatch $domainPingBatch)
    {
    }

    public function update(Request $request, DomainPingBatch $domainPingBatch)
    {
    }

    public function destroy(DomainPingBatch $domainPingBatch)
    {
    }
}
