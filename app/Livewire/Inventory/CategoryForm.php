<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryForm extends Component
{
    public $categories;
    public $category_id;
    public $name, $description, $type;
    public $showForm = false;

    public function mount()
    {
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.inventory.category-form');
    }

    public function loadCategories()
    {
        $this->categories = Category::latest()->get();
    }

    public function resetForm()
    {
        $this->category_id = null;
        $this->name = '';
        $this->description = '';
        $this->type = '';
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $cat = Category::findOrFail($id);
        $this->category_id = $cat->id;
        $this->name = $cat->name;
        $this->description = $cat->description;
        $this->type = $cat->type;
        $this->showForm = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
        ]);

        if ($this->category_id) {
            Category::findOrFail($this->category_id)->update(array_merge($validated, ['updated_by' => Auth::id()]));
        } else {
            Category::create(array_merge($validated, ['created_by' => Auth::id()]));
        }

        $this->resetForm();
        $this->showForm = false;
        $this->loadCategories();
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->loadCategories();
    }
}
