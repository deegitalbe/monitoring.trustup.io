<x-guest-layout>
    <div>
        @if ($running_domain_ping_batch)
            <a class="text-blue-400 hover:underline" href="{{ route('domain-pings-batches.show', ['domain_pings_batch' => $running_domain_ping_batch]) }}">Running batch started {{ $running_domain_ping_batch->started_at->diffForHumans() }}</a>
        @elseif ($domain_pings_batch->finished_at)
            <a class="text-orange-400 font-bold">Warning: no batch is running</a>
        @endif

        @if ($domain_pings_batch)
            <h1 class="text-2xl font-bold">Pinged domains from the batch started {{ $domain_pings_batch->started_at->diffForHumans() }}</h1>

            @if ($domain_pings_batch->finished_at)
                <p>Finished, duration : <b>{{ $domain_pings_batch->finished_at->longAbsoluteDiffForHumans($domain_pings_batch->started_at) }}</b></p>
            @endif

            <p class="mb-8 font-light">
                {{ !$domain_pings_batch->finished_at ? 'Running...' : '' }}
                Pinged {{ $domain_pings_batch->domain_pings->count() }} out of {{ $domain_pings_batch->domain_count }}
            </p>

            <div class="flex space-x-8">
                <div class="w-1/2">
                    <h1 class="text-xl mb-4">Bad status ({{ count($domain_pings_batch->domain_pings->where('status', '!=', 200)) }} pinged)</h1>
                    <x-domain-ping-table class="w-full" :domain-pings="$domain_pings_batch->domain_pings->where('status', '!=', 200)->sortByDesc('answer_time_ms')"></x-domain-ping-table>
                </div>

                <div class="w-1/2">
                    <h1 class="text-xl mb-4">200 status ({{ count($domain_pings_batch->domain_pings->where('status', 200)) }} pinged)</h1>
                    <x-domain-ping-table class="w-full" :domain-pings="$domain_pings_batch->domain_pings->where('status', 200)->sortByDesc('answer_time_ms')"></x-domain-ping-table>
                </div>
            </div>
        @else
            <h1 class="text-2xl font-bold">No running batch</h1>
        @endif
    </div>
</x-guest-layout>
