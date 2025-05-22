<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 20mm;
        }
        .invoice-container {
            max-width: 210mm;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }
        .company-info {
            text-align: right;
        }
        .company-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .bill-to, .payment-info {
            width: 48%;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            text-align: left;
            padding: 10px;
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: 600;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-partial {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }
        .signature-container {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin-top: 60px;
        }
        .notes {
            margin-top: 30px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div>
                <div class="invoice-title">INVOICE</div>
                <div>#: {{ $invoice->invoice_number }}</div>
                <div>Date: {{ $invoice->invoice_date }}</div>
                <div>Status: <span class="status {{ $invoice->status == 'paid' ? 'status-paid' : ($invoice->status == 'partial' ? 'status-partial' : 'status-unpaid') }}">{{ $invoice->status }}</span></div>
            </div>
            <div class="company-info">
                <div class="company-name">{{ $company->name }}</div>
                <div>{{ $company->address_line_1 }}</div>
                <div>{{ $company->address_line_2 }}</div>
                <div>{{ $company->city }}, {{ $company->state }} {{ $company->postal_code }}</div>
                <div>Phone: {{ $company->phone }}</div>
                <div>Email: {{ $company->email }}</div>
            </div>
        </div>

        <div class="details-row">
            <div class="bill-to">
                <div class="section-title">Bill To:</div>
                <div>{{ $invoice->customer->name }}</div>
                <div>{{ $invoice->customer->address }}</div>
                <div>{{ $invoice->customer->email }}</div>
                <div>{{ $invoice->customer->phone }}</div>
            </div>
            <div class="payment-info">
                <div class="section-title">Payment Information:</div>
                <div>Method: {{ $invoice->payment_method }}</div>
                <div>Currency: {{ $invoice->currency }}</div>
            </div>
        </div>

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
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <!-- Items will be dynamically inserted here -->
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
                {{-- {{/each}} --}}
            </tbody>
        </table>

        <table>
            <tr class="total-row">
                <td colspan="7" class="text-right">Subtotal</td>
                <td class="text-right">{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="7" class="text-right">Discount</td>
                <td class="text-right">{{ number_format($invoice->discount, 2) }}</td>
            </tr>
            {{-- {{#each taxes}} --}}
            @foreach($invoice->taxables as $tax)
                <tr class="total-row">
                    <td colspan="7" class="text-right">{{ $tax['tax_name'] }} ({{ rtrim(rtrim($tax['rate'], '0'), '.') }}%)</td>
                    <td class="text-right">{{ number_format($tax['amount'], 2) }}</td>
                </tr>
            @endforeach
            {{-- {{/each}} --}}
            <tr class="total-row">
                <td colspan="7" class="text-right">Total</td>
                <td class="text-right">{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="7" class="text-right">Paid</td>
                <td class="text-right">{{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="7" class="text-right">Due</td>
                <td class="text-right">{{ number_format($invoice->due_amount, 2)  }}</td>
            </tr>
        </table>

        {{-- {{#if notes}} --}}
        <div class="notes">
            <strong>Notes:</strong> {{ $invoice->notes }}
        </div>
        {{-- {{/if}} --}}

        {{-- {{#if signature}} --}}
        @if($invoice->signature)
            <div class="signature-container">
                <div>Customer Signature:</div>
                <img src="{{ public_path('storage/' . $invoice->signature->signature_path) }}" alt="Customer Signature" style="max-width: 200px; max-height: 80px;">
            </div>
        @else
        <div class="signature-container">
            <div>Customer Signature:</div>
            <div class="signature-line"></div>
        </div>
        @endif
    </div>
</body>
</html>