@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Leads Create</h4>
            <div class="flex space-x-2">
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('leads.index')}}">
                  <i class="fa fa-arrow-circle-left"></i> Back
                </a>
              </button>
            </div>
        </div>
        <!-- Form -->
        <form action="{{ route('leads.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Enter your name">
                    </div>
                    
                    <!-- Email Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="email@example.com">
                    </div>

                    <!-- Mobile Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mobile Number
                        </label>
                        <input type="text" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Enter your mobile number">
                    </div>

                    <!-- Source Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Source
                        </label>
                        <input type="text" name="source" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150" placeholder="Enter your source">
                    </div>
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
