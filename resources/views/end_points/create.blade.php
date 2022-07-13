<x-guest-layout>

    <h1 class="text-3xl font-bold mb-6">Create an end-points</h1>

    <form method="POST" action="{{ route('end-points.store') }}">
        @csrf

        <div class="flex flex-col space-y-6">
            <div>
                <label for="name" class="block pl-2 mb-0.5">Name</label>
                <input value="{{ old('name') }}" type="text" name="name" id="name" class="p-2 w-[450px] outline-0 border border-neutral-200 rounded" placeholder="www.trustup.be">
                @error('name')
                    <div class="text-red-500 font-bold">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="url" class="block pl-2 mb-0.5">Url</label>
                <input {{ old('url') }} type="text" name="url" id="url" class="p-2 w-[450px] outline-0 border border-neutral-200 rounded" placeholder="https://trustup.be">
                @error('url')
                    <div class="text-red-500 font-bold">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <input type="checkbox" name="is_monitored" id="is_monitored" class="p-2 outline-0 border border-neutral-200 rounded" checked placeholder="https://trustup.be">
                <label for="is_monitored" class="pl-2 mb-0.5">Monitor ?</label>
                @error('is_monitored')
                    <div class="text-red-500 font-bold">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <input type="checkbox" name="is_staging" id="is_staging" class="p-2 outline-0 border border-neutral-200 rounded" placeholder="https://trustup.be">
                <label for="is_staging" class="pl-2 mb-0.5">Is staging ?</label>
                @error('is_staging')
                    <div class="text-red-500 font-bold">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="w-min bg-blue-600 rounded px-4 py-2 text-white hover:bg-blue-800 transition-colors">Submit</button>
        </div>
    </form>
</x-guest-layout>
