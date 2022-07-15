<x-guest-layout>

    <div class="mb-8 md:mb-16">
        <h1 class="text-2xl md:text-3xl font-bold">{{ $endPoint->name }}</h1>
        <a class="text-blue-400 hover:underline" href="{{ route('end-points.index') }}">All endPoints</a>
    </div>


    <div class="mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($health_check_categories as $category)
                @php
                    $last_health_check = $endPoint->last_health_check_by_name($category->name);
                @endphp
                <a href="{{ route('end-points.show', ['end_point' => $endPoint, 'health_check' => $category->name]) }}" class="p-4 shadow-md transition-colors hover:bg-gray-100 rounded-xl bg-white" data-bs-toggle="tooltip" title="{{ $last_health_check?->notification_message }}">
                    <div class="flex items-start space-x-4 mb-2">
                        <i class="fas fa-circle text-sm mt-1 {{ $last_health_check?->status == 'ok' ? 'text-green-400' : 'text-red-400' }}"></i>
                        <div class="w-full">
                            <div class="flex justify-between items-end w-full">
                                <h2 class="text-slate-800 text-xl font-bold">{{ $category->label }}</h2>
                                <h3 class="text-slate-400 text-sm">{{ \Illuminate\Support\Carbon::createFromDate($last_health_check?->finished_at)->diffForHumans() }}</h3>
                            </div>
                            <h3 class="text-slate-600">{{ $last_health_check?->short_summary }}</h3>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    @if ($health_check)
        <div class="w-full bg-white p-6">
            {{ $health_check->view() }}
        </div>
    @endif
</x-guest-layout>
