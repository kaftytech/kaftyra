<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Vendors;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::all();
        return view('inventory.stock.index', compact('stocks'));
    }

    public function create()
    {
        return view('inventory.stock.create', [
            'products' => Product::all(),
            'vendors' => Vendors::all(),
        ]);
    }
    public function store(Request $request)
    {
        Stock::create($request->all());

        return redirect()->route('stock.index')->with('success', 'Stock created successfully.');
    }
    public function show(Stock $stock)
    {
        return view('inventory.stock.show', compact('stock'));
    }
    public function edit(Stock $stock)
    {
        return view('inventory.stock.edit', compact('stock'));
    }
    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $stock->update($request->all());

        return redirect()->route('stock.index')->with('success', 'Stock updated successfully.');
    }
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stock.index')->with('success', 'Stock deleted successfully.');
    }
}
