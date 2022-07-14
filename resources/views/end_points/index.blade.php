<x-guest-layout>

    <div class="mb-8 md:mb-16">
        <h1 class="text-2xl md:text-3xl font-bold">Welcome to monitoring.trustup.io</h1>
        <a class="text-blue-400 hover:underline" href="{{ route('end-points.create') }}">Create an end-point</a>
    </div>


    <div class="mb-8">
        <form action="{{ route('end-points.index') }}" method="GET" class="flex space-x-2 items-center">
            <button type="submit" class="w-min bg-blue-600 rounded px-4 py-2 text-white hover:bg-blue-800 transition-colors">Filter</button>
            <input type="text" name="search_name" id="search_namename" placeholder="Search..." value="{{ request()->search_name }}" class="p-2 outline-0 border border-neutral-200 rounded">
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="show_staging" name="show_staging" class="rounded" {{ request()->boolean('show_staging') ? 'checked' : '' }}>
                <label for="show_staging">Show staging</label>
            </div>
        </form>
    </div>
    <div class="flex flex-col space-y-8">
        @foreach ($endPoints as $endPoint)
            <div class="bg-white rounded-lg p-8 relative ">

                @if ($endPoint->has_failed())
                    <div class="h-full w-4 bg-red-600 absolute left-0 top-0 rounded-l-lg animate-pulse shadow-xl shadow-red-500"></div>
                @endif
                @if ($endPoint->has_no_data())
                    <div class="h-full w-4 bg-orange-400 absolute left-0 top-0 rounded-l-lg animate-pulse shadow-xl shadow-red-500"></div>
                @endif

                {{-- TITLE SECTION --}}
                <div class="flex flex-col md:flex-row justify-between">
                    <div class="flex items-center space-x-6">
                        <i class="fas fa-circle {{ $endPoint->is_monitored ? 'text-green-400' : 'text-red-400' }}"></i>
                        <a class="text-xl md:text-2xl font-medium -mt-1 hover:underline" href="{{ route('end-points.show', ['end_point' => $endPoint]) }}">
                            @if ($endPoint->is_staging)
                                <span class="text-gray-600 text-sm">[STAGING]</span>
                            @endif
                            {{ $endPoint->name }}
                        </a>
                    </div>
                    <div class="flex flex-col space-y-0 sm:space-y-0 sm:flex-row sm:items-center sm:space-x-2 text-slate-300">
                        <h3 class="md:text-lg">{{ $endPoint->url }}{{ $endPoint->ping_default_url ? '/trustup-io/health/ping' : '' }} -</h3>
                        @if ($endPoint->is_monitored && $endPoint->last_health_check_timestamp)
                            <h3 class="font-light">{{ $endPoint->last_health_check_timestamp->diffForHumans() }}</h3>
                        @elseif ($endPoint->has_failed())
                            <h3 class="text-red-500 font-medium">Fetching health checks failed</h3>
                        @endif
                        <form action="{{ route('end-points.destroy', ['end_point' => $endPoint]) }}" method="POST" x-data="" x-ref="delete_endPoint_{{ $endPoint->id }}" class="sm:ml-2">
                            @method('DELETE')
                            @csrf
                            <button x-on:click.prevent="if (confirm('Delete endpoint ?')) $refs.delete_endPoint_{{ $endPoint->id }}.submit()"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <hr class="border-0 border-b border-b-gray-200 mt-2 mb-6">

                {{-- CONTENT SECTION--}}
                @if (!$endPoint->last_health_check_timestamp)
                    No data has been recorded yet
                @elseif ($endPoint->is_monitored && !$endPoint->health_checks_failed)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($endPoint->last_health_checks() as $result)
                            <div class="p-4 shadow-md rounded-xl" data-bs-toggle="tooltip" title="{{ $result->notification_message }}">
                                <div class="flex items-start space-x-4 mb-2">
                                    <i class="fas fa-circle text-sm mt-1 {{ $result->status == 'ok' ? 'text-green-400' : 'text-red-400' }}"></i>
                                    <div>
                                        <h2 class="text-slate-800 text-xl font-bold ">{{ $result->label }}</h2>
                                        <h3 class="text-slate-600">{{ $result->short_summary }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-guest-layout>
