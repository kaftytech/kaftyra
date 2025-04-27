@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Company Create</h4>
            {{-- <div class="flex space-x-2">
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('vendors.index')}}">
                  <i class="fa fa-arrow-circle-left"></i> Back
                </a>
              </button>
            </div> --}}
        </div>
        <!-- Form -->
        <form action="{{ route('vendors.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Name -->
                <x-input label="Company Name" name="company_name" />

                <!-- Company Registration Number -->
                <x-input label="Registration Number" name="company_registration_number" />

                <!-- Email -->
                <x-input label="Email" name="email" type="email" />

                <!-- Phone -->
                <x-input label="Phone" name="phone" />

                <!-- Address -->
                <x-input label="Address" name="address" />

                <!-- Notes -->
                <x-input label="Notes" name="notes" />

                <!-- City -->
                <x-input label="City" name="city" />

                <!-- State -->
                <x-input label="State" name="state" />

                <!-- Country -->
                <x-input label="Country" name="country" />

                <!-- Zip Code -->
                <x-input label="Zip Code" name="zip_code" />

                <!-- Bank Account Number -->
                <x-input label="Bank Account Number" name="bank_account_number" />

                <!-- Bank IFSC Code -->
                <x-input label="Bank IFSC Code" name="bank_ifsc_code" />

                <!-- Contact Person -->
                <x-input label="Contact Person" name="contact_person" />

                <!-- Contact Email -->
                <x-input label="Contact Email" name="contact_email" type="email" />

                <!-- Contact Phone -->
                <x-input label="Contact Phone" name="contact_phone" />

                <!-- Contact Mobile -->
                <x-input label="Contact Mobile" name="contact_mobile" />

                <!-- Contact Address -->
                <x-input label="Contact Address" name="contact_address" />                    
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
