<x-guest-layout>

    <div class="mb-8 md:mb-16">
        <h1 class="text-2xl md:text-3xl font-bold">Welcome to monitoring.trustup.io</h1>
        <a class="text-blue-400 hover:underline" href="{{ route('end-points.create') }}">Create an end-point</a>
    </div>

    <div class="flex flex-col space-y-8">
        @foreach ($endPoints as $endPoint)
            <div class="bg-white rounded-lg p-8 relative ">

                @if ($endPoint->has_failed())
                    <div class="h-full w-4 bg-red-600 absolute left-0 top-0 rounded-l-lg animate-pulse shadow-xl shadow-red-500"></div>
                @endif

                {{-- TITLE SECTION --}}
                <div class="flex flex-col md:flex-row justify-between">
                    <div class="flex items-center space-x-6">
                        <i class="fas fa-circle {{ $endPoint->is_monitored ? 'text-green-400' : 'text-red-400' }}"></i>
                        <h2 class="text-xl md:text-2xl font-medium -mt-1">{{ $endPoint->name }}</h2>
                    </div>
                    <div class="flex flex-col space-y-0 sm:space-y-0 sm:flex-row sm:items-center sm:space-x-2 text-slate-300">
                        <h3 class="md:text-lg">{{ $endPoint->url }} -</h3>
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
