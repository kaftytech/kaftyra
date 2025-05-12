@extends('layouts.app')
@section('content')
<div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Customer Create</h4>
            <div class="flex space-x-2">
                <a href="{{ route('customers.index') }}" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                    <i class="fa fa-arrow-circle-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Form Start -->
        <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf

            <!-- 🔹 Personal Details -->
            <h5 class="text-lg font-semibold text-gray-600 mb-2 border-b pb-1">Personal Details</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <x-input label="Customer Name" name="customer_name" />
                <x-input label="Email" name="email" type="email" />
                <x-input label="Phone" name="phone" />
                <x-input label="Profile Picture" name="profile_picture" type="file" />
                <x-textarea label="Contact Address" name="contact_address" />
            </div>

            <!-- 🔹 Company Details -->
            <h5 class="text-lg font-semibold text-gray-600 mb-2 border-b pb-1">Company Details</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Company Name" name="company_name" />
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
                <x-input label="Bank Account Holder Name" name="bank_account_holder_name" />
                <x-input label="Bank Account Type" name="bank_account_type" />
                <x-input label="Payment Terms" name="payment_terms" />
                <x-input label="Payment Method" name="payment_method" />
                <x-input label="Currency" name="currency" />
                <x-select label="Status" name="status" :options="['active' => 'Active', 'inactive' => 'Inactive', 'blocked' => 'Blocked']" />
                <x-select label="Type" name="type" :options="['customer' => 'Customer', 'client' => 'Client', 'contractor' => 'Contractor']" />
                <x-input label="Rating" name="rating" />
                <x-input label="Website URL" name="website_url" />
                <x-input label="Logo" name="logo" type="file" />
                <x-textarea label="Notes" name="notes" />
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end">
                <button class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-150">
                    <i class="fa fa-save"></i> Save
                </button>
                <a href="{{ route('customers.index') }}" class="ml-2 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-150">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
