<x-guest-layout>
    <div>
        <h1 class="text-2xl font-bold">List of the domain ping batches</h1>

        <table class="w-full">
            <thead>
            <tr class="text-left">
                <th class="pr-6"></th>
                <th class="px-6">Started</th>
                <th class="px-6">Finished</th>
                <th class="px-6">Duration</th>
                <th class="px-6">Count domains</th>
                <th class="px-6">Failed domain</th>
                <th class="pl-6">Success domain</th>
            </tr>
            </thead>
            <tbody class="bg-white border-t-2 border-black">
            @foreach($domain_ping_batches as $domain_ping_batch)
                <tr>
                    <td class="pr-6 pl-2 py-0.5">
                        <i class="fas fa-circle text-sm mt-1 {{ $domain_ping_batch->finished_at ? 'text-green-400' : (!$domain_ping_batch->failed ? 'text-blue-300 animate-ping' : 'text-red-400' )}}"></i>
                        <a class="ml-1 text-blue-300 hover:text-blue-500" href="{{ route('domain-pings-batches.show', ['domain_pings_batch' => $domain_ping_batch]) }}"><i class="fas fa-eye"></i></a>
                    </td>

                    <td class="px-6 py-0.5">
                        {{ $domain_ping_batch->started_at->format('d/m/Y H:i:s') }}
                    </td>

                    <td class="px-6 py-0.5">
                        {{ $domain_ping_batch->finished_at?->format('d/m/Y H:i:s') }}
                    </td>

                    <td class="px-6 py-0.5">
                        {{ $domain_ping_batch->finished_at ? $domain_ping_batch->finished_at->longAbsoluteDiffForHumans($domain_ping_batch->started_at) : '' }}
                    </td>

                    <td class="px-6 py-0.5">
                        {{ $domain_ping_batch->domain_pings_count }} / {{ $domain_ping_batch->domain_count }}
                        ({{ round(($domain_ping_batch->domain_pings_count / $domain_ping_batch->domain_count) * 100) }}%)
                    </td>

                    <td class="px-6 py-0.5">
                        {{ $domain_ping_batch->domain_pings_failed_count }}
                    </td>

                    <td class="pl-6 py-0.5">
                        {{ $domain_ping_batch->domain_pings_success_count }}
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</x-guest-layout>
