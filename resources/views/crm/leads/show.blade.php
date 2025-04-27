@extends('layouts.app')  <!-- Assuming you have a main layout -->

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg">
    <div class="flex justify-between items-center p-4 border-b">
        <h4 class="font-medium text-gray-700">Leads Details</h4>
        <div class="flex space-x-2">
          <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
            <a href="{{route('leads.index')}}">
              <i class="fa fa-arrow-circle-left"></i> Back
            </a>
          </button>
        </div>
    </div>

    <div class="grid grid-cols-1 p-4 md:grid-cols-3 gap-6">
        <!-- Name -->
        <div class="border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Name</h3>
            <p class="text-gray-600">{{ $lead->name }}</p>
        </div>
    
        <!-- Email -->
        <div class="border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Email</h3>
            <p class="text-gray-600">{{ $lead->email ?? 'N/A' }}</p>
        </div>
    
        <!-- Phone -->
        <div class="border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Phone</h3>
            <p class="text-gray-600">{{ $lead->phone ?? 'N/A' }}</p>
        </div>
    
        <!-- Source -->
        <div class="border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Source</h3>
            <p class="text-gray-600">{{ $lead->source ?? 'N/A' }}</p>
        </div>
    
        <!-- Status -->
        <div class="border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Status</h3>
            <span class="px-4 py-1 inline-block text-white bg-{{ $lead->status == 'new' ? 'blue' : ($lead->status == 'contacted' ? 'green' : ($lead->status == 'converted' ? 'purple' : 'gray')) }}-500 rounded-full text-sm">
                {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
            </span>
        </div>
    
        <!-- Notes -->
        <div class="col-span-2 border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Notes</h3>
            <p class="text-gray-600">{{ $lead->notes ?? 'No additional notes.' }}</p>
        </div>

        <!-- Created At -->
        <div class="border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Created At</h3>
            <p class="text-gray-600">{{ $lead->created_at->format('d M Y, H:i') }}</p>
        </div>
        <!-- Updated At -->
        <div class="border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Updated At</h3>
            <p class="text-gray-600">{{ $lead->updated_at->format('d M Y, H:i') }}</p>
        </div>

    </div>
    
    @if($lead->status !== 'converted')
    <div class="flex justify-start space-x-4 mt-6">
        <!-- Convert to Vendor Button -->
        <a href="{{ route('leads.convert', ['lead' => $lead->id, 'type' => 'vendor']) }}" 
            class="bg-blue-600 text-white py-1 px-2 rounded text-sm hover:bg-blue-700">
            Convert to Vendor
        </a>
        
        <!-- Convert to Customer Button -->
        <a href="{{ route('leads.convert', ['lead' => $lead->id, 'type' => 'customer']) }}" 
            class="bg-green-600 text-white py-1 px-2 rounded text-sm hover:bg-green-700">
            Convert to Customer
        </a>
    </div>
    @endif
    
    
</div>
@endsection
