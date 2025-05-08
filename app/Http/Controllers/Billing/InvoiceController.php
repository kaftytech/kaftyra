<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\InvoiceItem;
use App\Models\Company;
use App\Models\InvoiceSignature;
use App\Models\OrderRequest;
use Illuminate\Support\Facades\Storage;
use PDF;
class InvoiceController extends Controller
{
    public function index()
    {
        return view('billing.invoice.index');
    }

    public function create()
    {
        return view('billing.invoice.create');
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('billing.invoice.edit', compact('invoice'));
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoiceItems = InvoiceItem::where('invoice_id', $invoice->id)->get();
        $company = Company::first();

        return view('billing.invoice.show', compact('invoice', 'invoiceItems', 'company'));
    }
    public function saveSignature(Request $request, Invoice $invoice)
    {
        $request->validate([
            'signature' => 'required|string',
        ]);
    
        // Parse the base64 data
        $imageData = $request->input('signature');
        preg_match("/data:image\/(.*?);base64,(.*)/", $imageData, $matches);
    
        if (count($matches) !== 3) {
            return response()->json(['error' => 'Invalid signature data'], 400);
        }
    
        $extension = $matches[1]; // e.g. png
        $base64Data = base64_decode($matches[2]);
    
        $fileName = 'signature_' . time() . '.' . $extension;
        $filePath = 'invoices/' . $fileName;
    
        Storage::disk('public')->put($filePath, $base64Data);
    
        // Save in DB
        InvoiceSignature::create([
            'invoice_id' => $invoice->id,
            'signature_path' => $filePath,
        ]);
    
        return response()->json(['message' => 'Signature saved!',     'redirect' => route('invoices.show', $invoice->id),]);
    }

    public function generatePDF(Invoice $invoice)
    {
        // Load the invoice with its relationships
        $invoice->load(['customer', 'items.product']);
        
        // Get company details from settings
        $company = Company::first(); // Adjust based on your settings model
        
        // Prepare data for PDF view
        $data = [
            'invoice' => $invoice,
            'invoiceItems' => $invoice->items,
            'company' => $company,
            'title' => 'Invoice #' . $invoice->invoice_number,
        ];
        
        // Generate PDF using the invoice-pdf view
        $pdf = PDF::loadView('invoices.pdf', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('a5');
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
            'defaultFont' => 'Arial',
            'dpi' => 120,
            'defaultMediaType' => 'print',
            'isFontSubsettingEnabled' => true,
        ]);
        
        // Return the PDF for download
        return $pdf->download('Invoice-' . $invoice->invoice_number . '.pdf');
    }
    
    /**
     * View PDF invoice in browser
     * 
     * @param Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function viewPDF(Invoice $invoice)
    {
        // Load the invoice with its relationships
        $invoice->load(['customer', 'items.product']);
        
        // Get company details from settings
        $company = Company::first(); // Adjust based on your settings model
        
        // Prepare data for PDF view
        $data = [
            'invoice' => $invoice,
            'invoiceItems' => $invoice->items,
            'company' => $company,
            'title' => 'Invoice #' . $invoice->invoice_number,
        ];
        
        // Generate PDF using the invoice-pdf view
        $pdf = PDF::loadView('billing.invoice.pdf', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('a4');
        
        // Stream PDF in the browser
        return $pdf->stream('Invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function convertInvoice($orderId)
    {
        return view('billing.invoice.create', compact('orderId'));
    }
    
        
}
