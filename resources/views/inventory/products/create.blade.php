@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Product Create</h4>
            <div class="flex space-x-2">
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('products.index')}}">
                  <i class="fa fa-arrow-circle-left"></i> Back
                </a>
              </button>
            </div>
        </div>
        <!-- Form -->
        <form action="{{ route('products.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Input -->
                    <x-input label="Product Name" name="name"/>

                    <x-input label="Product Code" name="product_code"/>                 
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Unit
                        </label>
                        <select name="unit_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>

                     <!-- Category Select -->
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Category
                        </label>
                        <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-input label="MRP" name="mrp" type="number" />
                    <x-input label="Selling Price" name="selling_price" type="number" />
                    <x-input label="GST %" name="gst_percentage" type="number" />
                    <x-textarea label="Description" name="description" />
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-150">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button class="ml-2 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-150">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                </div>
            </div>    
        </form>    
    </div>
</div>
@endsection
