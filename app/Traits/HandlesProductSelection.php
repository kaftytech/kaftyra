<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Vendors;

trait HandlesProductSelection
{
    public function handleProductSearch(&$items, $index, $query,$vendorId = null)
    {
        $items[$index]['search_status'] = 'typing';
        $items[$index]['highlight_index'] = 0;
        if ($vendorId) {
            // Fetch products related to vendor
            $vendor = Vendors::with(['products' => function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('product_code', 'like', "%{$query}%");
            }])->find($vendorId);
    
            $products = $vendor?->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'product_code' => $product->product_code,
                    'vendor_price' => $product->pivot->vendor_price ?? null,
                ];
            })->take(10)->values()->toArray();
        } else {
            // Fallback: no vendor selected
            $products = Product::where('name', 'like', "%{$query}%")
                ->orWhere('product_code', 'like', "%{$query}%")
                ->limit(10)
                ->get(['id', 'name', 'product_code'])
                ->toArray();
        }
    
        $items[$index]['search_results'] = $products;
    }

    public function selectProduct(&$items, $index, $productId, $calculateTotalCallback = null)
    {
        foreach ($items as $i => $item) {
            if ($i !== $index && ($item['product_id'] ?? null) == $productId) {
                return "This product is already selected in another row.";
            }
        }
        $product = Product::find($productId);
        if (!$product) return;

        $stock = $product->stock()->first(); // Assuming relationship

        $items[$index] = array_merge($items[$index], [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_code' => $product->product_code,
            'product_search' => $product->name . ' (' . $product->product_code . ')',
            'search_status' => 'selected',
            'price' => $product->selling_price,
            'available_stock' => $stock?->current_stock ?? 0,
            'tax_percentage' => $product->gst_percentage,
        ]);

        if (is_callable($calculateTotalCallback)) {
            call_user_func($calculateTotalCallback, $index);
        }

        return null; // no error
    }
    public function selectVendorProduct(&$items, $index, $productId, $calculateTotalCallback = null, $vendorId = null)
    {
        // Prevent duplicate product selection
        foreach ($items as $i => $item) {
            if ($i !== $index && ($item['product_id'] ?? null) == $productId) {
                return "This product is already selected in another row.";
            }
        }
    
        $product = Product::with('vendors')->find($productId);
        if (!$product) return;
    
        $vendorPrice = null;
    
        if ($vendorId) {
            // Look up vendor_price from pivot
            $vendor = $product->vendors->firstWhere('id', $vendorId);
            $vendorPrice = $vendor?->pivot?->vendor_price;
        }
    
        $stock = $product->stock()->first(); // optional: only if you use stock info
    
        $items[$index] = array_merge($items[$index], [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_code' => $product->product_code,
            'product_search' => $product->name . ' (' . $product->product_code . ')',
            'search_status' => 'selected',
            'price' => $vendorPrice ?? $product->selling_price,
            'available_stock' => $stock?->current_stock ?? 0,
        ]);
    
        if (is_callable($calculateTotalCallback)) {
            call_user_func($calculateTotalCallback, $index);
        }
    
        return null; // no error
    }
    

    public function highlightProductNext(&$items, $index)
    {
        if (!empty($items[$index]['search_results'])) {
            $max = count($items[$index]['search_results']) - 1;
            if ($items[$index]['highlight_index'] < $max) {
                $items[$index]['highlight_index']++;
            }
        }
    }

    public function highlightProductPrevious(&$items, $index)
    {
        if ($items[$index]['highlight_index'] > 0) {
            $items[$index]['highlight_index']--;
        }
    }

    public function selectHighlightedProduct(&$items, $index, $selectCallback)
    {
        $highlight = $items[$index]['highlight_index'] ?? 0;
        $productList = $items[$index]['search_results'] ?? [];

        if (isset($productList[$highlight]) && is_callable($selectCallback)) {
            call_user_func($selectCallback, $index, $productList[$highlight]['id']);
        }
    }
}
