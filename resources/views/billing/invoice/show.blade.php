@extends('layouts.app')

@section('content')
 <!-- Main Content -->
 <div class="p-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center p-4 border-b">
            <h4 class="font-medium text-gray-700">Invoice Show</h4>
            <div class="flex space-x-2">
              <button class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                <a href="{{route('invoices.index')}}">
                  <i class="fa fa-arrow-circle-left"></i> Back
                </a>
              </button>
            </div>
        </div>
        <div class="invoice-preview p-8 bg-white rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-indigo-600 mb-4">INVOICE</h3>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-gray-800">Invoice #:</span> {{ $invoice->invoice_number }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-gray-800">Date:</span> {{ date('d M, Y', strtotime($invoice->invoice_date)) }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-gray-800">Status:</span> 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status == 'partial' > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $invoice->status == 'paid' ? 'Paid' : ($invoice->status == 'partial'? 'Partial' : 'Unpaid') }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $company->name }}</h4>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p>{{ $company->address_line_1 . ' ,' . $company->address_line_2 }}</p>
                        <p>{{ $company->city . ', ' . $company->state . ' ' . $company->postal_code }}</p> 
                        <p>Phone: {{ $company->phone ?? '' }}</p>
                        <p>Email: {{ $company->email ?? '' }}</p>
                    </div>
                </div>
            </div>
        
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Bill To:</h5>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p class="font-medium text-gray-800">{{ $invoice->customer->name }}</p>
                            <p>{{ $invoice->customer->address ?? '' }}</p>
                            <p>{{ $invoice->customer->email ?? '' }}</p>
                            <p>{{ $invoice->customer->phone ?? '' }}</p>
                        </div>
                </div>
                <div class="text-right">
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Payment Information:</h5>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p><span class="font-medium text-gray-800">Method:</span> {{ $invoice->payment_method ?? 'Not specified' }}</p>
                        <p><span class="font-medium text-gray-800">Currency:</span> {{ $invoice->currency }}</p>
                    </div>
                </div>
            </div>
        
            <div class="mt-8 overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">#</th>
                            <th class="px-4 py-2 text-left font-semibold">Product</th>
                            <th class="px-4 py-2 text-left font-semibold">Product Code</th>
                            <th class="px-4 py-2 text-left font-semibold">Price</th>
                            <th class="px-4 py-2 text-left font-semibold">Quantity</th>
                            <th class="px-4 py-2 text-left font-semibold">Discount</th>
                            <th class="px-4 py-2 text-left font-semibold">Tax</th>
                            <th class="px-4 py-2 text-left font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($invoiceItems as $index => $item)
                            @if(!empty($item['product_id']))
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $item->product->name }}</td>
                                    <td class="px-4 py-2">{{ $item->product->product_code ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">{{ number_format($item->discount ?? 0, 2) }}</td>
                                    <td class="px-4 py-2">
                                        {{ number_format($item->tax_amount ?? 0, 2) }}
                                        <span class="text-xs text-gray-400">({{ $item->tax_percentage ?? 0 }}%)</span>
                                    </td>
                                    <td class="px-4 py-2 font-semibold">{{ number_format($item->net_total, 2) }}</td>
                                </tr>
                            @endif
                        @endforeach
        
                        <!-- Totals -->
                        <tr class="border-t">
                            <td colspan="7" class="px-4 py-2 text-right font-bold">Subtotal</td>
                            <td class="px-4 py-2 font-semibold">{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-right font-bold">Discount</td>
                            <td class="px-4 py-2 font-semibold">{{ number_format($invoice->discount, 2) }}</td>
                        </tr>
                        @foreach($invoice->taxables as $tax)
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-right font-bold">{{ $tax['tax_name'] }}({{ rtrim(rtrim($tax['rate'], '0'), '.') }}%)</td>
                            <td class="px-4 py-2 font-semibold">{{ number_format($tax['amount'], 2) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-right font-bold">Total</td>
                            <td class="px-4 py-2 font-semibold">{{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-right font-bold text-green-600">Paid</td>
                            <td class="px-4 py-2 font-semibold">{{ number_format($invoice->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-right font-bold text-red-500">Due</td>
                            <td class="px-4 py-2 font-semibold">{{ number_format($invoice->due_amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <form action="{{ route('shipping.store') }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                
                <div class="flex items-center space-x-2">
                    <label for="initiate_shipping" class="text-sm font-medium text-gray-700">Initiate Shipping?</label>
                    <select name="initiate_shipping" id="initiate_shipping" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                        Submit
                    </button>
                </div>
            </form>
        </div>
             
    </div>
 </div>

@endsection