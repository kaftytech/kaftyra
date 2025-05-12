@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Purchase Bills</h4>
            <div class="flex space-x-2">
            </div>
          </div>
        <!-- Table -->
        @livewire('inventory.billing-form', ['bill_id' => $purchaseBill->id])
    </div>
 </div>
@endsection
