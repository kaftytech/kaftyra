<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category; // Assuming you have a Unit model

class CategoryController extends Controller
{
    public function index()
    {
        // Fetch all categories from the database
        // Assuming you have a Category model
        $categories = Category::paginate(10); // Fetch all categories from the database
        return view('inventory.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('inventory.categories.create');
    }

    public function store(Request $request)
    {
        // Handle the request to store a new category
        // Validate and save the category data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            // Add other validation rules as needed
        ]);
        $input = $request->except('_token');
        // Create a new category
        // Assuming you have a Category model
        Category::create($input);
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show($id)
    {
        return view('inventory.categories.show', compact('id'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::paginate(10); 
        return view('inventory.categories.index', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Handle the request to update the category
        // Validate and update the category data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            // Add other validation rules as needed
        ]);
        $input = $request->except('_token');
        // Find the category by ID and update it
        $category = Category::findOrFail($id);
        $category->update($input);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        // Handle the request to delete the category
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
