<table {{ $attributes->merge() }}>
    <thead>
    <tr class="text-left">
        <th class="pl-2"></th>
        <th class="px-2">URL</th>
        <th class="px-2">DNS A</th>
        <th class="px-2">Status</th>
        <th class="pr-2">Time</th>
    </tr>
    </thead>
    <tbody class="bg-white border-t-2 border-black">
        @foreach($domainPings as $domainPing)
            <tr>
                <td class="pl-2 py-0.5">
                    <i class="fas fa-circle text-sm mt-1 {{ $domainPing->status == 200 ? ($domainPing->answer_time_ms >= 3000 ? 'text-orange-400' : 'text-green-400') : 'text-red-400' }}"></i>
                </td>

                <td class="px-2 py-0.5 whitespace-nowrap">
                    <a class="hover:underline" href="{{ $domainPing->url }}" target="_blank" data-bs-toggle="tooltip" title="{{ $domainPing->url }}">
                        {{ \Illuminate\Support\Str::limit($domainPing->url, 40) }}
                    </a>
                </td>
                <td class="px-2 py-0.5 text-gray-500" data-bs-toggle="tooltip" title="{{ $domainPing->dns_a }}">
                    {{ \Illuminate\Support\Str::limit($domainPing->dns_a, 7) }}
                </td>

                <td class="px-2 py-0.5 whitespace-nowrap" data-bs-toggle="tooltip" title="{{ $domainPing->status_reason }}">
                    {{ $domainPing->status != -1 ? $domainPing->status : \Illuminate\Support\Str::limit($domainPing->status_reason, 10) }}
                </td>

                <td class="pr-2 py-0.5">
                    {{ $domainPing->answer_time_ms }}ms
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
