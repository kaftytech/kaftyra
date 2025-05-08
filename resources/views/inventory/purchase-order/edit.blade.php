@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Purchase Order Edit</h4>
          </div>
        <!-- Table -->
        @livewire('inventory.purchase-order.purchase-order-form', ['purchaseOrderId' => $purchaseOrder->id])
    </div>
 </div>
@endsection
