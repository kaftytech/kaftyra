<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        return view('inventory.products.index');
    }

    public function create()
    {
        $units = Unit::all();
        $categories = Category::all();
        return view('inventory.products.create', compact('units', 'categories'));
    }
    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        return view('inventory.products.show', compact('product'));
    }
    public function edit(Product $product)
    {
        $units = Unit::all();
        $categories = Category::all();
        return view('inventory.products.edit', compact('product', 'units', 'categories'));
    }
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return redirect()->route('products.index');
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ProductImport, $request->file('file'));

        return back()->with('success', 'Products imported successfully.');
    }
}
