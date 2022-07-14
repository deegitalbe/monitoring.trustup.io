<table {{ $attributes->merge() }}>
    <thead>
    <tr class="text-left">
        <th class="pl-4"></th>
        <th class="px-4">URL</th>
        <th class="px-4">DNS A</th>
        <th class="px-4">Status</th>
        <th class="pr-4">Time</th>
    </tr>
    </thead>
    <tbody class="bg-white border-t-2 border-black">
        @foreach($domainPings as $domainPing)
            <tr>
                <td class="pl-4 py-0.5">
                    <i class="fas fa-circle text-sm mt-1 {{ $domainPing->status == 200 ? ($domainPing->answer_time_ms >= 3000 ? 'text-orange-400' : 'text-green-400') : 'text-red-400' }}"></i>
                </td>

                <td class="px-4 py-0.5 whitespace-nowrap">
                    <a class="hover:underline" href="{{ $domainPing->url }}" target="_blank">
                        {{ \Illuminate\Support\Str::limit($domainPing->url, 40) }}
                    </a>
                </td>
                <td class="px-4 py-0.5 text-gray-500">
                    {{ $domainPing->dns_a }}
                </td>

                <td class="px-4 py-0.5 whitespace-nowrap">
                    {{ $domainPing->status != -1 ? $domainPing->status : 'Host not found' }}
                </td>

                <td class="pr-4 py-0.5">
                    {{ $domainPing->answer_time_ms }}ms
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
