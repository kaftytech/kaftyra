@extends('layouts.app')
@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Customer Create</h4>
            <div class="flex space-x-2">
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('customers.index')}}">
                  <i class="fa fa-arrow-circle-left"></i> Back
                </a>
              </button>
            </div>
        </div>
        <!-- Form -->
        <form action="{{ route('customers.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="p-4">
            <!-- Customer Form -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <x-input label="Customer Name" name="customer_name" />

                <x-input label="Email" name="email" type="email" />
                <x-input label="Phone" name="phone" />

                <x-textarea label="Address Line 1" name="address_line_1" />
                <x-textarea label="Address Line 2" name="address_line_2" />

                <x-input label="City" name="city" />
                <x-input label="State" name="state" />
                <x-input label="Country" name="country" />
                <x-input label="Zip Code" name="zip_code" />

                <x-input label="VAT Number" name="vat_number" />
                <x-input label="GST Number" name="gst_number" />

                <x-input label="Bank Account Number" name="bank_account_number" />
                <x-input label="Bank Name" name="bank_name" />
                <x-input label="Bank Branch" name="bank_branch" />
                <x-input label="Bank IFSC Code" name="bank_ifsc_code" />
                <x-input label="Bank SWIFT Code" name="bank_swift_code" />
                <x-input label="Bank Account Holder Name" name="bank_account_holder_name" />
                <x-input label="Bank Account Type" name="bank_account_type" />

                <x-input label="Payment Terms" name="payment_terms" />
                <x-input label="Payment Method" name="payment_method" />
                <x-input label="Currency" name="currency" />

                <x-select label="Status" name="status" :options="['active' => 'Active', 'inactive' => 'Inactive', 'blocked' => 'Blocked']" />
                <x-select label="Type" name="type" :options="['customer' => 'Customer', 'client' => 'Client', 'contractor' => 'Contractor']" />

                <x-input label="Rating" name="rating" />
                <x-input label="Category" name="category" />
                <x-input label="Tags" name="tags" />

                <x-input label="Website URL" name="website_url" />
                <x-input label="Social Media Links" name="social_media_links" />

                <x-input label="Logo" name="logo" type="file" />
                <x-input label="Profile Picture" name="profile_picture" type="file" />

                <x-input label="Contact Person" name="contact_person" />
                <x-input label="Contact Email" name="contact_email" type="email" />
                <x-input label="Contact Phone" name="contact_phone" />
                <x-input label="Contact Mobile" name="contact_mobile" />
                <x-textarea label="Contact Address" name="contact_address" />
                
                <x-textarea label="Notes" name="notes" />

                {{-- <x-select label="Lead" name="lead_id" :options="$leads" option-value="id" option-label="name" /> --}}

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
