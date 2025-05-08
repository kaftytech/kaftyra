<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 40px 30px 40px 30px; /* top, right, bottom, left */
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h3, h4, h5 {
            margin: 0;
            padding: 0;
        }
        .header, .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .totals td {
            font-weight: bold;
        }
        .status {
            display: inline-block;
            padding: 2px 6px;
            font-size: 11px;
            border-radius: 4px;
        }
        .paid { background-color: #d4edda; color: #155724; }
        .partial { background-color: #fff3cd; color: #856404; }
        .unpaid { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body style="border: 1px solid #000;
    padding: 20px;
    height: 100%;
    box-sizing: border-box; ">

    <table style="width: 100%; border: 1px solid #ccc; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            {{-- Invoice Details --}}
            <td style="width: 33%; vertical-align: top; border: 1px solid #ccc; padding: 10px;">
                <h3 style="margin: 0 0 10px;">INVOICE</h3>
                <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Date:</strong> {{ date('d M, Y', strtotime($invoice->invoice_date)) }}</p>
                <p><strong>Status:</strong>
                    <span style="
                        font-weight: bold;
                        color: {{ $invoice->status == 'paid' ? 'green' : ($invoice->status == 'partial' ? 'orange' : 'red') }};
                    ">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </p>
            </td>
    
            {{-- From --}}
            <td style="width: 33%; vertical-align: top; border: 1px solid #ccc; padding: 10px;">
                <h4 style="margin: 0 0 10px;">From:</h4>
                <p><strong>{{ $company->name }}</strong></p>
                <p>{{ $company->address_line_1 }} {{ $company->address_line_2 }}</p>
                <p>{{ $company->city }}, {{ $company->state }} {{ $company->postal_code }}</p>
                <p>Phone: {{ $company->phone }}</p>
                <p>Email: {{ $company->email }}</p>
            </td>
    
            {{-- To --}}
            <td style="width: 33%; vertical-align: top; border: 1px solid #ccc; padding: 10px;">
                <h4 style="margin: 0 0 10px;">Bill To:</h4>
                <p><strong>{{ $invoice->customer->name }}</strong></p>
                <p>{{ $invoice->customer->address }}</p>
                <p>{{ $invoice->customer->email }}</p>
                <p>{{ $invoice->customer->phone }}</p>
            </td>
        </tr>
    </table>
    

    <h4 class="section-title">Items:</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Code</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($invoiceItems as $index => $item)
            @if(!empty($item['product_id']))
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->product->product_code ?? '-' }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->discount ?? 0, 2) }}</td>
                <td>{{ number_format($item->tax_amount ?? 0, 2) }} ({{ $item->tax_percentage ?? 0 }}%)</td>
                <td>{{ number_format($item->net_total, 2) }}</td>
            </tr>
            @endif
        @endforeach

        <!-- Totals -->
        <tr class="totals">
            <td colspan="7" class="text-right">Subtotal</td>
            <td>{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr class="totals">
            <td colspan="7" class="text-right">Discount</td>
            <td>{{ number_format($invoice->discount, 2) }}</td>
        </tr>
        @foreach($invoice->taxables as $tax)
        <tr class="totals">
            <td colspan="7" class="text-right">{{ $tax['tax_name'] }} ({{ rtrim(rtrim($tax['rate'], '0'), '.') }}%)</td>
            <td>{{ number_format($tax['amount'], 2) }}</td>
        </tr>
        @endforeach
        <tr class="totals">
            <td colspan="7" class="text-right">Total</td>
            <td>{{ number_format($invoice->total_amount, 2) }}</td>
        </tr>
        <tr class="totals">
            <td colspan="7" class="text-right">Paid</td>
            <td>{{ number_format($invoice->paid_amount, 2) }}</td>
        </tr>
        <tr class="totals">
            <td colspan="7" class="text-right">Due</td>
            <td>{{ number_format($invoice->due_amount, 2) }}</td>
        </tr>
        </tbody>
    </table>

    @if($invoice->signature)
    <div class="section">
        <h4 class="section-title">Customer Signature:</h4>
        <img src="{{ public_path('storage/' . $invoice->signature->signature_path) }}" alt="Signature" style="width: 200px; height: auto; margin-top: 10px; border: 1px solid #ccc;">
    </div>
    @endif

</body>
</html>
