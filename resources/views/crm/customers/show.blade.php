@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg">
    <div class="flex justify-between items-center p-4 border-b">
        <h4 class="font-medium text-gray-700">Customer Details</h4>
        <div class="flex space-x-2">
            <a href="{{ route('customers.edit', $customer->id) }}" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <i class="fa fa-edit"></i> Edit
            </a>
            <a href="{{ route('customers.index') }}" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <i class="fa fa-arrow-circle-left"></i> Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4">
        <!-- Basic Info -->
        <x-detail title="Customer Name" :value="$customer->customer_name" />
        <x-detail title="Email" :value="$customer->email" />
        <x-detail title="Phone" :value="$customer->phone" />

        <!-- Address -->
        <x-detail title="Address Line 1" :value="$customer->address_line_1" />
        <x-detail title="Address Line 2" :value="$customer->address_line_2" />
        <x-detail title="City" :value="$customer->city" />
        <x-detail title="State" :value="$customer->state" />
        <x-detail title="Country" :value="$customer->country" />
        <x-detail title="Zip Code" :value="$customer->zip_code" />

        <!-- Tax & Bank -->
        <x-detail title="VAT Number" :value="$customer->vat_number" />
        <x-detail title="GST Number" :value="$customer->gst_number" />

        <x-detail title="Bank Account Number" :value="$customer->bank_account_number" />
        <x-detail title="Bank Name" :value="$customer->bank_name" />
        <x-detail title="Bank Branch" :value="$customer->bank_branch" />
        <x-detail title="IFSC Code" :value="$customer->bank_ifsc_code" />
        <x-detail title="SWIFT Code" :value="$customer->bank_swift_code" />
        <x-detail title="Account Holder Name" :value="$customer->bank_account_holder_name" />
        <x-detail title="Account Type" :value="$customer->bank_account_type" />

        <!-- Payment -->
        <x-detail title="Payment Terms" :value="$customer->payment_terms" />
        <x-detail title="Payment Method" :value="$customer->payment_method" />
        <x-detail title="Currency" :value="$customer->currency" />

        <!-- Meta -->
        <x-detail title="Status" :value="ucfirst($customer->status)" />
        <x-detail title="Type" :value="ucfirst($customer->type)" />
        <x-detail title="Rating" :value="$customer->rating" />
        <x-detail title="Category" :value="$customer->category" />
        <x-detail title="Tags" :value="$customer->tags" />

        <!-- Online presence -->
        <x-detail title="Website URL" :value="$customer->website_url" />
        <x-detail title="Social Media Links" :value="$customer->social_media_links" />

        <!-- Images -->
        <div class="border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Logo</h3>
            @if($customer->logo)
                <img src="{{ asset('storage/' . $customer->logo) }}" alt="Logo" class="w-32 h-auto mt-2">
            @else
                <p class="text-gray-500">No logo uploaded</p>
            @endif
        </div>

        <div class="border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Profile Picture</h3>
            @if($customer->profile_picture)
                <img src="{{ asset('storage/' . $customer->profile_picture) }}" alt="Profile Picture" class="w-32 h-auto mt-2">
            @else
                <p class="text-gray-500">No profile picture uploaded</p>
            @endif
        </div>

        <!-- Contact Person -->
        <x-detail title="Contact Person" :value="$customer->contact_person" />
        <x-detail title="Contact Email" :value="$customer->contact_email" />
        <x-detail title="Contact Phone" :value="$customer->contact_phone" />
        <x-detail title="Contact Mobile" :value="$customer->contact_mobile" />
        <x-detail title="Contact Address" :value="$customer->contact_address" />

        <!-- Notes -->
        <div class="md:col-span-3 border p-4 rounded-lg shadow-sm bg-white">
            <h3 class="text-lg font-semibold text-gray-700">Notes</h3>
            <p class="text-gray-600">{{ $customer->notes ?? 'No notes available.' }}</p>
        </div>
    </div>
</div>
@endsection
