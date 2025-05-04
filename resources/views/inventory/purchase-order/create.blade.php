@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Purchase Order Create</h4>
            <div class="flex space-x-2">
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('order-requests.create')}}">
                  <i class="fas fa-plus mr-1"></i> Add
                </a>
              </button>
            </div>
          </div>
        <!-- Table -->
        @livewire('inventory.purchase-order.purchase-order-form')
    </div>
 </div>
@endsection
