<div>      
    <div class="overflow-x-auto mt-4">
      <div class="flex items-center mb-4 ml-2 gap-6">
        <x-input type="text" name="search" wire:model.live="search" placeholder="Search..." />
        <x-select name="readStatus" :options="['all' => 'All', 'read' => 'Read', 'unread' => 'Unread']" wire:model.live="readStatus" />
        <x-select name="perPage" :options="['10' => '10', '25' => '25', '50' => '50', '100' => '100']" wire:model.live="perPage" />
      </div>
  
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th> --}}
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Read At</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($notifications as $notification)
          <tr>
            {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $notification->id }}</td> --}}
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $notification->data['message'] ?? '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              @if ($notification->read_at)
                <span class="px-2 py-1 inline-flex text-xs leading-5 rounded-full bg-green-100 text-green-800">Read</span>
              @else
                <span class="px-2 py-1 inline-flex text-xs leading-5 rounded-full bg-yellow-100 text-yellow-800">Unread</span>
              @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ $notification->read_at ? $notification->read_at->format('Y-m-d H:i') : '-' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ $notification->created_at->format('Y-m-d H:i') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
              @if ($notification->read_at)
                <button wire:click="markAsUnread('{{ $notification->id }}')" class="text-yellow-600 hover:text-yellow-900">
                  <i class="fas fa-envelope-open-text"></i>
                </button>
              @else
                <button wire:click="markAsRead('{{ $notification->id }}')" class="text-green-600 hover:text-green-900">
                  <i class="fas fa-envelope"></i>
                </button>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No notifications found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
  
      <div class="mt-4">
        {{ $notifications->links() }}
      </div>
    </div>
  </div>
  