@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Products</h4>
            <div class="flex space-x-2">
              <form id="importProductForm" action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" style="display: inline;">
                  @csrf
                  <input type="file" name="file" id="productFileInput" class="hidden" onchange="document.getElementById('importProductForm').submit();" required>
              
                  <button type="button" 
                          onclick="document.getElementById('productFileInput').click();" 
                          class="bg-green-600 text-white py-1 px-3 rounded text-sm hover:bg-green-700">
                      <i class="fas fa-plus mr-1"></i> Import
                  </button>
              </form>
            
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('products.create')}}">
                  <i class="fas fa-plus mr-1"></i> Add
                </a>
              </button>
            </div>
          </div>
        <!-- Table -->
        @livewire('inventory.product-table')
    </div>
 </div>
@endsection
