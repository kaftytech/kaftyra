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
        <div class="mt-8 flex space-x-4">
            <a href="{{ route('invoices.pdf.view', $invoice->id) }}" target="_blank" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View PDF
            </a>
            
            <a href="{{ route('invoices.pdf.download', $invoice->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download PDF
            </a>
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
                @if($invoice->signature)
                    <p>Customer Signature</p>
                    <img 
                        src="{{ asset('storage/' . $invoice->signature->signature_path) }}" 
                        class="mt-4 w-64 h-32 object-contain border border-gray-300 rounded" 
                        alt="Signature">
                @endif
            
            </div>
            @if(!$invoice->signature)
                <h3 class="text-xl font-semibold mb-4">Customer Signature</h3>
                <canvas id="signature-pad" class="border border-gray-400 w-full rounded" style="height: 200px;"></canvas>
        
                <div class="mt-4 flex gap-4">
                    <button onclick="clearSignature()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Clear
                    </button>
                    <button onclick="previewSignature()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Preview Signature
                    </button>
                    <button onclick="storeSignature()" id="save-btn" style="display:none;">Confirm & Save</button>
                </div>
        
                <!-- Output (optional) -->
                <div id="output" class="mt-4"></div>
            @endif
        </div>
             
    </div>
 </div>
 <script>
    let signaturePad;
    let lastSignatureData = null;

    window.addEventListener('load', () => {
        const canvas = document.getElementById('signature-pad');
        canvas.width = canvas.offsetWidth;
        canvas.height = 200;
        signaturePad = new SignaturePad(canvas);
    });

    function clearSignature() {
        signaturePad.clear();
        document.getElementById('output').innerHTML = '';
        document.getElementById('save-btn').style.display = 'none';
        lastSignatureData = null;
    }

    function previewSignature() {
        if (signaturePad.isEmpty()) {
            alert("Please provide a signature first.");
            return;
        }

        // Get the signature image
        const dataURL = signaturePad.toDataURL();
        lastSignatureData = dataURL;

        // Preview the image
        document.getElementById('output').innerHTML = `
            <p class="text-sm text-gray-600">Signature preview:</p>
            <img src="${dataURL}" class="mt-2 border rounded max-w-full" />
        `;

        // Show "Confirm & Save" button
        document.getElementById('save-btn').style.display = 'inline-block';
    }

    function storeSignature() {
        if (!lastSignatureData) {
            alert("No signature to save. Please preview first.");
            return;
        }

        fetch('/billing/invoices/{{ $invoice->id }}/save-signature', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ signature: lastSignatureData })
        })
        .then(response => response.json())
        .then(data => {
            window.location.href = data.redirect;
        })

        .catch(error => {
            console.error('Error saving signature:', error);
        });
    }
</script>
@endsection