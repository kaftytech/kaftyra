@extends('layouts.app')
@section('content')
<div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Customer Edit</h4>
            <div class="flex space-x-2">
                <a href="{{ route('customers.index') }}" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                    <i class="fa fa-arrow-circle-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Edit Form Start -->
        <form action="{{ route('customers.update', $customers->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            @method('PUT')

            <!-- 🔹 Personal Details -->
            <h5 class="text-lg font-semibold text-gray-600 mb-2 border-b pb-1">Personal Details</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <x-input label="Customer Name" name="customer_name" :value="$customers->customer_name" />
                <x-input label="Email" name="email" type="email" :value="$customers->email" />
                <x-input label="Phone" name="phone" :value="$customers->phone" />
                <x-input label="Profile Picture" name="profile_picture" type="file" />
                <x-textarea label="Contact Address" name="contact_address" :value="$customers->contact_address" />
            </div>

            <!-- 🔹 Company Details -->
            <h5 class="text-lg font-semibold text-gray-600 mb-2 border-b pb-1">Company Details</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Company Name" name="company_name" :value="$customers->company_name" />
                <x-textarea label="Address Line 1" name="address_line_1" :value="$customers->address_line_1" />
                <x-textarea label="Address Line 2" name="address_line_2" :value="$customers->address_line_2" />
                <x-input label="City" name="city" :value="$customers->city" />
                <x-input label="State" name="state" :value="$customers->state" />
                <x-input label="Country" name="country" :value="$customers->country" />
                <x-input label="Zip Code" name="zip_code" :value="$customers->zip_code" />
                <x-input label="VAT Number" name="vat_number" :value="$customers->vat_number" />
                <x-input label="GST Number" name="gst_number" :value="$customers->gst_number" />
                <x-input label="Bank Account Number" name="bank_account_number" :value="$customers->bank_account_number" />
                <x-input label="Bank Name" name="bank_name" :value="$customers->bank_name" />
                <x-input label="Bank Branch" name="bank_branch" :value="$customers->bank_branch" />
                <x-input label="Bank IFSC Code" name="bank_ifsc_code" :value="$customers->bank_ifsc_code" />
                <x-input label="Bank Account Holder Name" name="bank_account_holder_name" :value="$customers->bank_account_holder_name" />
                <x-input label="Bank Account Type" name="bank_account_type" :value="$customers->bank_account_type" />
                <x-input label="Payment Terms" name="payment_terms" :value="$customers->payment_terms" />
                <x-input label="Payment Method" name="payment_method" :value="$customers->payment_method" />
                <x-input label="Currency" name="currency" :value="$customers->currency" />
                <x-select label="Status" name="status" :value="$customers->status" :options="['active' => 'Active', 'inactive' => 'Inactive', 'blocked' => 'Blocked']" />
                <x-select label="Type" name="type" :value="$customers->type" :options="['customer' => 'Customer', 'client' => 'Client', 'contractor' => 'Contractor']" />
                <x-input label="Rating" name="rating" :value="$customers->rating" />
                <x-input label="Website URL" name="website_url" :value="$customers->website_url" />
                <x-input label="Logo" name="logo" type="file" />
                <x-textarea label="Notes" name="notes" :value="$customers->notes" />
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end">
                <button class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-150">
                    <i class="fa fa-save"></i> Update
                </button>
                <a href="{{ route('customers.index') }}" class="ml-2 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-150">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
