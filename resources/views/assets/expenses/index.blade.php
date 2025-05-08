@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Expenses</h4>
          </div>
        <!-- Table -->
        @livewire('asset.expense-manager')
    </div>
 </div>
@endsection
