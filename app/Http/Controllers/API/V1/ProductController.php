<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('name')
            ->paginate(20);
            
        return response()->json($products);
    }
    
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);
        
        $products = Product::where('name', 'like', '%' . $request->query . '%')
            ->orWhere('sku', 'like', '%' . $request->query . '%')
            ->orderBy('name')
            ->paginate(10);
            
        return response()->json($products);
    }
}