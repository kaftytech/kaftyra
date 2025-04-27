@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Leads Edit</h4>
            <div class="flex space-x-2">
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('leads.index')}}">
                  <i class="fa fa-arrow-circle-left"></i> Back
                </a>
              </button>
            </div>
        </div>
        <!-- Edit Form -->
        <form action="{{ route('leads.update', $lead->id) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input type="text" name="name" value="{{ old('name', $lead->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Enter your name">
                    </div>
                    
                    <!-- Email Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="email@example.com">
                    </div>

                    <!-- Mobile Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mobile Number
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Enter your mobile number">
                    </div>

                     <!-- Source -->
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Source
                        </label>
                        <input type="text" name="source" value="{{ old('source', $lead->source) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Enter your Source">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                            <option value="">Select Status</option>
                            <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="converted" {{ old('status', $lead->status) == 'converted' ? 'selected' : '' }}>Converted</option>
                            <option value="not_interested" {{ old('status', $lead->status) == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                            <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-150">
                        <i class="fa fa-save"></i> Update
                    </button>
                    <a href="{{ route('leads.index') }}" class="ml-2 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-150">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    
    </div>
</div>
@endsection
