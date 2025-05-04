<div class="p-6 sm:p-8 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Audit Logs</h2>
        {{-- <div class="mt-4 sm:mt-0">
            <input
                type="text"
                placeholder="Search logs..."
                class="w-72 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            >
        </div> --}}
    </div>

    <!-- Audit Logs -->
    <div class="space-y-6">
        @foreach($auditLogs as $log)
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-xl font-semibold text-indigo-700">Log #{{ $log->id }}</h3>
                    <p class="text-sm text-gray-500">By: {{ $log->user?->name ?? 'N/A' }}</p>
                </div>
                <span class="text-sm text-gray-400">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
            </div>

            <!-- Event & IP -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <p class="text-gray-600 font-medium">Event</p>
                    <p class="text-gray-800">{{ $log->event }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-medium">IP Address</p>
                    <p class="text-gray-800">{{ $log->ip_address }}</p>
                </div>
            </div>

            <!-- Comments -->
            @if($log->comments)
            <div class="mb-4">
                <p class="text-gray-600 font-medium">Comments</p>
                <p class="text-gray-800">{{ $log->comments }}</p>
            </div>
            @endif

            <!-- Changes -->
            @if(!empty($log->new_values))
            <div class="mt-6 border-t pt-4">
                <h4 class="text-gray-700 font-semibold mb-4">Changes</h4>
                <div class="space-y-4">
                    @foreach(json_decode($log->new_values) as $key => $newValue)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border">
                        <div>
                            <p class="text-sm text-gray-500">Field</p>
                            <p class="font-semibold text-gray-800">{{ $key }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">New Value</p>
                            <p class="text-green-700">{!! $newValue !!}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Old Value</p>
                            <p class="text-red-700">
                                @isset(json_decode($log->old_values)->$key)
                                    {!! json_decode($log->old_values)->$key !!}
                                @else
                                    N/A
                                @endisset
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $auditLogs->links() }}
    </div>
</div>
