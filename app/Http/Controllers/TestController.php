<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
class TestController extends Controller
{

    public function store(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'invoice_file' => 'required|file|mimes:jpg,jpeg,png,pdf',
        // ]);

        $path = $request->file('invoice_file')->store('invoices', 'public');

        $fullPath = storage_path('app/public/' . $path);

        // Extract text using OCR
        $ocrText = (new TesseractOCR($fullPath))->run();

        // Parse the text
        $parsed = $this->parseInvoiceText($ocrText);
        dd($parsed);

        // Save purchase order
        $po = PurchaseOrder::create([
            'vendor_name' => $parsed['vendor'],
            'order_date' => $parsed['date'],
            'total_amount' => $parsed['total'],
            'invoice_file_path' => $path,
        ]);

        foreach ($parsed['items'] as $item) {
            $po->items()->create($item);
        }

        return back()->with('success', 'Invoice processed and saved.');
    }

    private function parseInvoiceText($text)
    {
        // Dummy pattern – customize for your vendor's bill format
        preg_match('/Date[:\s]+([0-9\/\-]+)/i', $text, $dateMatch);
        preg_match('/Total[:\s]+(\d+\.\d{2})/i', $text, $totalMatch);

        // Example for item lines:
        preg_match_all('/([A-Za-z0-9 ]+)\s+(\d+)\s+(\d+\.\d{2})/', $text, $itemMatches, PREG_SET_ORDER);

        $items = [];
        foreach ($itemMatches as $m) {
            $items[] = [
                'item_name' => trim($m[1]),
                'quantity' => (int)$m[4],
                'unit_price' => (float)$m[6],
                'total_price' => (int)$m[4] * (float)$m[6],
            ];
        }

        return [
            'vendor' => 'AutoDetected Vendor', // Add logic if needed
            'date' => $dateMatch[1] ?? now(),
            'total' => $totalMatch[1] ?? 0,
            'items' => $items,
        ];
    }


}
