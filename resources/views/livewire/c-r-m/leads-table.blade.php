<div>      
  <div class="overflow-x-auto mt-4">
    <div class="flex items-center mb-4 ml-2 gap-6">
      <x-input type="text" name="search" wire:model.live="search" placeholder="Search..." />
      <x-select name="status" :options="['all' => 'All', 'new' => 'New', 'contacted' => 'Contacted', 'converted' => 'Converted', 'lost' => 'Lost']" wire:model.live="status" />
      <x-select name="perPage" :options="['10' => '10', '25' => '25', '50' => '50', '100' => '100']" wire:model.live="perPage" />
    </div>
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Follow Up Date</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @forelse($leads as $lead)
        <tr>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->id }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->name }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->email }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->phone }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->follow_up_date }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm">
            <span class="px-2 py-1 inline-flex text-xs leading-5 rounded-full bg-green-100 text-green-800">
              {{ $lead->status }}
            </span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->created_at }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
            <button class="text-blue-600 hover:text-blue-900">
              <a href="{{route('leads.edit',$lead->id)}}">
                <i class="fas fa-edit"></i>
              </a>
            </button>
            <button class="ml-2 text-gray-600 hover:text-gray-900">
              <a href="{{route('leads.show',$lead->id)}}">
                <i class="fas fa-eye"></i>
              </a>
            </button>
            <button class="ml-2 text-red-600 hover:text-red-900">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No records found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  
    <div class="mt-4">
      {{ $leads->links() }}
    </div>
  </div>
</div>