@extends('layouts.app')
@section('content')
<div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Tax Settings</h4>            
        </div>

        <!-- Create / Edit Form -->
        <form action="{{ isset($tax) ? route('tax.update', $tax->id) : route('tax.store') }}" method="POST" class="mt-4">
            @csrf
            @if(isset($tax))
                @method('PUT')
            @endif

            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <x-input label="Tax Name" name="name" :value="old('name', $tax->name ?? '')" />

                    <!-- Rate -->
                    <x-input label="Tax Rate (%)" name="rate" type="number" step="0.01" :value="old('rate', $tax->rate ?? '')" />

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="percentage" {{ (old('type', $tax->type ?? '') == 'percentage') ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ (old('type', $tax->type ?? '') == 'fixed') ? 'selected' : '' }}>Fixed</option>
                        </select>
                    </div>

                    <!-- Active -->
                    <div class="flex items-center space-x-2 mt-6">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tax->is_active ?? true) ? 'checked' : '' }}>
                        <label class="text-sm text-gray-700">Is Active</label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700">
                        <i class="fa fa-save"></i> {{ isset($tax) ? 'Update' : 'Create' }}
                    </button>
                    @if(isset($tax))
                        <a href="{{ route('tax.index') }}" class="ml-2 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto mt-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Rate (%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Created At</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($taxes as $tax)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $tax->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $tax->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $tax->rate }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($tax->type) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($tax->is_active)
                                <span class="text-green-600">Active</span>
                            @else
                                <span class="text-red-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $tax->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('tax.edit', $tax->id) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('tax.destroy', $tax->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No tax records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $taxes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
