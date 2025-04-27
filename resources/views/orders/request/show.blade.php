@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-sm">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Order Details</h2>
        <a href="{{ route('order-requests.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm inline-flex items-center">
            <i class="fa fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <!-- Order Information -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <x-order-info title="Order ID" :value="$order->order_id" />
        <x-order-info title="Customer Name" :value="$order->customer->customer_name ?? 'N/A'" />
        <x-order-info title="Request Date" :value="$order->request_date ?? 'N/A'" />

        <div class="border p-4 rounded-lg bg-gray-50">
            <h4 class="text-sm font-semibold text-gray-600 mb-2">Status</h4>
            <span class="px-4 py-1 inline-block text-white rounded-full text-sm bg-{{ 
                $order->status == 'pending' ? 'blue' : 
                ($order->status == 'approved' ? 'green' : 
                ($order->status == 'rejected' ? 'red' : 'gray')) 
            }}-500">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>

        <x-order-info title="Approved Date" :value="$order->approved_date ? \Carbon\Carbon::parse($order->approved_date)->format('d M Y') : 'N/A'" />
        <x-order-info title="Approved By" :value="$order->approved_by ?? 'N/A'" />
        
        <x-order-info title="Created At" :value="$order->created_at->format('d M Y, H:i')" />
        <x-order-info title="Updated At" :value="$order->updated_at->format('d M Y, H:i')" />

    </div>

    <!-- Notes Section -->
    @if($order->notes)
    <div class="mb-10">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Notes</h3>
        <div class="p-4 border rounded-lg bg-gray-50 text-gray-700 leading-relaxed">
            {{ $order->notes }}
        </div>
    </div>
    @endif

    <!-- Order Items Table -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S.No</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orderItems as $item)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $item->product->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $item->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-center text-gray-500 text-sm">No items found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
