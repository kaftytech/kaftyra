@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Units</h4>            
          </div>
          <form action="{{ isset($unit) ? route('units.update', $unit->id) : route('units.store') }}" method="POST" class="mt-4">
            @csrf
            @if(isset($unit))
                @method('PUT')
            @endif
        
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Unit Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Unit Name
                        </label>
                        <input type="text" name="name" value="{{ old('name', $unit->name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="e.g., Kilogram">
                    </div>
        
                    <!-- Unit Symbol -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Unit Symbol
                        </label>
                        <input type="text" name="symbol" value="{{ old('symbol', $unit->symbol ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="e.g., kg">
                    </div>
                </div>
        
                <div class="mt-6 flex justify-end">
                    <button class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-150">
                        <i class="fa fa-save"></i> {{ isset($unit) ? 'Update' : 'Create' }}
                    </button>
                    <a href="{{ route('units.index') }}" class="ml-2 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-150">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Symbol</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @forelse($units as $unit)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $unit->id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $unit->name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $unit->symbol }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $unit->created_at }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                    <button class="text-blue-600 hover:text-blue-900">
                      <a href="{{route('units.edit',$unit->id)}}">
                        <i class="fas fa-edit"></i>
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
              {{ $units->links() }}
            </div>
        </div>
        
    </div>
 </div>
@endsection
