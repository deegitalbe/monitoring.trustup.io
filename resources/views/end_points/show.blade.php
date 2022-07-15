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
                <a href="{{ route('end-points.show', ['end_point' => $endPoint, 'health_check' => $category->name,  'date_range' => 'h']) }}" class="p-4 shadow-md transition-colors hover:bg-gray-200 rounded-xl {{ request()->health_check == $category->name ? 'bg-gray-200' :'bg-white' }}" data-bs-toggle="tooltip" title="{{ $last_health_check?->notification_message }}">
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
            <div class="mb-4 flex justify-end space-x-2">
                <a href="{{ route('end-points.show', ['end_point' => $endPoint, 'health_check' => request()->health_check, 'date_range' => 'h']) }}" class="{{ request()->date_range == 'h' ? 'bg-gray-400' : 'bg-gray-200' }} hover:bg-gray-400 transition-colors py-2 px-4 rounded">Last hour</a>
                <a href="{{ route('end-points.show', ['end_point' => $endPoint, 'health_check' => request()->health_check, 'date_range' => 'd']) }}" class="{{ request()->date_range == 'd' ? 'bg-gray-400' : 'bg-gray-200' }} hover:bg-gray-400 transition-colors py-2 px-4 rounded">Last day</a>
                <a href="{{ route('end-points.show', ['end_point' => $endPoint, 'health_check' => request()->health_check, 'date_range' => 'm']) }}" class="{{ request()->date_range == 'm' ? 'bg-gray-400' : 'bg-gray-200' }} hover:bg-gray-400 transition-colors py-2 px-4 rounded">Last month</a>
                <a href="{{ route('end-points.show', ['end_point' => $endPoint, 'health_check' => request()->health_check, 'date_range' => '3m']) }}" class="{{ request()->date_range == '3m' ? 'bg-gray-400' : 'bg-gray-200' }} hover:bg-gray-400 transition-colors py-2 px-4 rounded">Last 3 month</a>
                <a href="{{ route('end-points.show', ['end_point' => $endPoint, 'health_check' => request()->health_check, 'date_range' => 'y']) }}" class="{{ request()->date_range == 'y' ? 'bg-gray-400' : 'bg-gray-200' }} hover:bg-gray-400 transition-colors py-2 px-4 rounded">Last year</a>
                <a href="{{ route('end-points.show', ['end_point' => $endPoint, 'health_check' => request()->health_check]) }}" class="{{ request()->date_range == null ? 'bg-gray-400' : 'bg-gray-200' }} hover:bg-gray-400 transition-colors py-2 px-4 rounded">All</a>
            </div>
            {{ $health_check->view() }}
        </div>
    @endif
</x-guest-layout>
