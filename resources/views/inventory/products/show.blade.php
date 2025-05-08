@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Product Details</h4>
            <div class="flex space-x-2">
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('products.index')}}">
                  <i class="fa fa-arrow-circle-left"></i> Back
                </a>
              </button>
            </div>
          </div>
          <div class="max-w-5xl mx-auto p-6 bg-white shadow rounded-lg">
            <!-- Product Header -->
            <div class="mb-6 border-b pb-4">
                <h2 class="text-2xl font-semibold text-gray-800">{{ $product->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $product->category->name ?? 'Uncategorized' }}</p>
            </div>
        
            <!-- Product Details Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h4 class="text-lg font-medium text-gray-700 mb-2">Description</h4>
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>
                <div>
                    <h4 class="text-lg font-medium text-gray-700 mb-2">Additional Info</h4>
                    <ul class="text-gray-600 space-y-1">
                        <li><strong>Product Code:</strong> {{ $product->product_code }}</li>
                        <li><strong>Price:</strong> ₹{{ number_format($product->selling_price, 2) }}</li>
                        <li><strong>Status:</strong>
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded 
                                         {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        
            <!-- Vendor Price Table -->
            <div>
                <h4 class="text-lg font-medium text-gray-700 mb-4">Vendor Prices</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border border-gray-200 rounded">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left border-b">Vendor Name</th>
                                <th class="px-4 py-2 text-left border-b">Price</th>
                                <th class="px-4 py-2 text-left border-b">Contact</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 text-sm">
                            @forelse ($product->vendors as $vendor)
                                <tr>
                                    <td class="px-4 py-2 border-b">{{ $vendor->company_name }}</td>
                                    <td class="px-4 py-2 border-b">₹{{ number_format($vendor->pivot->vendor_price, 2) }}</td>
                                    <td class="px-4 py-2 border-b">{{ $vendor->contact_person ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-500">No vendors available for this product.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
 </div>
@endsection
