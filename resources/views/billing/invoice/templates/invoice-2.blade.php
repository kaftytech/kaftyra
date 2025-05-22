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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 15mm;
        }
        .invoice-container {
            max-width: 210mm;
            margin: 0 auto;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }
        .invoice-header {
            background-color: #4f46e5;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }
        .invoice-meta {
            text-align: right;
        }
        .invoice-number {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .invoice-date {
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 5px;
        }
        .status-paid {
            background-color: #10b981;
        }
        .status-partial {
            background-color: #f59e0b;
        }
        .status-unpaid {
            background-color: #ef4444;
        }
        .company-info {
            padding: 25px 30px;
            background-color: #f9fafb;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
        }
        .company-details {
            flex: 1;
        }
        .company-name {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 5px;
        }
        .bill-to {
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 15px;
        }
        .customer-details p, .payment-details p {
            margin: 5px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: 600;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .totals-table td {
            padding: 8px 15px;
        }
        .total-label {
            font-weight: 600;
            color: #374151;
        }
        .total-value {
            font-weight: 600;
            text-align: right;
        }
        .grand-total {
            font-size: 16px;
            color: #4f46e5;
        }
        .paid-amount {
            color: #10b981;
        }
        .due-amount {
            color: #ef4444;
        }
        .footer {
            padding: 20px 30px;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
        }
        .notes {
            flex: 2;
        }
        .signature {
            flex: 1;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #9ca3af;
            margin: 40px auto 0;
            position: relative;
        }
        .signature-label {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f9fafb;
            padding: 0 10px;
            font-size: 12px;
            color: #6b7280;
        }
        .signature-img {
            max-width: 200px;
            max-height: 80px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-meta">
                <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
                <div class="invoice-date">{{ $invoice->invoice_date }}</div>
                <div class="status-badge {{ $invoice->status == 'paid' ? 'status-paid' : ($invoice->status == 'partial' ? 'status-partial' : 'status-unpaid') }}">{{ $invoice->status }}</div>
            </div>
        </div>

        <div class="company-info">
            <div class="company-details">
                <div class="company-name">{{ $company->name }}</div>
                <div>{{ $company->address_line_1 }}</div>
                <div>{{ $company->address_line_2 }}</div>
                <div>{{ $company->city }}, {{ $company->state }} {{ $company->postal_code }}</div>
                <div>Phone: {{ $company->phone }}</div>
                <div>Email: {{ $company->email }}</div>
            </div>
            <div class="payment-details">
                <div class="section-title">Payment Information</div>
                <p>Method: {{ $invoice->payment_method }}</p>
                <p>Currency: {{ $invoice->currency }}</p>
                {{-- <p>Due Date: {{  }}</p> --}}
            </div>
        </div>

        <div class="bill-to">
            <div class="customer-details">
                <div class="section-title">Bill To</div>
                <p>{{ $invoice->customer->name }}</p>
                <p>{{ $invoice->customer->address }}</p>
                <p>{{ $invoice->customer->email }}</p>
                <p>{{ $invoice->customer->phone }}</p>
            </div>
        </div>

        <div style="padding: 0 30px;">
            <table class="items-table">
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
                </tbody>
            </table>

            <table class="totals-table">
                <tr>
                    <td class="total-label">Subtotal</td>
                    <td class="total-value">{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label">Discount</td>
                    <td class="total-value">{{ number_format($invoice->discount, 2) }}</td>
                </tr>
                @foreach($invoice->taxables as $tax)
                <tr>
                    <td class="total-label">{{ $tax['tax_name'] }} ({{ rtrim(rtrim($tax['rate'], '0'), '.') }}%)</td>
                    <td class="total-value">{{ number_format($tax['amount'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="total-label grand-total">Total</td>
                    <td class="total-value grand-total">{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label paid-amount">Paid</td>
                    <td class="total-value paid-amount">{{ number_format($invoice->paid_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label due-amount">Due</td>
                    <td class="total-value due-amount">{{ number_format($invoice->due_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <div class="notes">
                <div class="section-title">Notes</div>
                <p>{{ $invoice->notes }}</p>
            </div>
            <div class="signature">
                @if($invoice->signature)
                <div>Customer Signature:</div>
                <img src="{{ public_path('storage/' . $invoice->signature->signature_path) }}" class="signature-img" alt="Signature">
               @else
                <div class="signature-line">
                    <span class="signature-label">Authorized Signature</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>