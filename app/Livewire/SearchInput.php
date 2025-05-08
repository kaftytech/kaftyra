<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SearchInput extends Component
{
    public $search_query = '';
    public $selected_item_id = null;
    public $search_results = [];
    public $highlighted_index = 0;
    public $model; // This will hold the model type (e.g., OrderRequest, Customer)
    public $search_field; // The field to search (e.g., 'customer_name', 'order_id')

    // Dynamic properties for the search input (can be customer_name, phone, etc.)
    public function mount($model, $search_field)
    {
        $this->model = $model;
        $this->search_field = $search_field;
    }

    public function updatedSearchQuery()
    {
        $this->highlighted_index = 0;

        // Dynamically query the model based on the search field
        $this->search_results = $this->model::where($this->search_field, 'like', '%' . $this->search_query . '%')
            ->get();
    }

    public function highlightNext()
    {
        if ($this->highlighted_index < count($this->search_results) - 1) {
            $this->highlighted_index++;
        }
    }

    public function highlightPrevious()
    {
        if ($this->highlighted_index > 0) {
            $this->highlighted_index--;
        }
    }

    public function selectItem()
    {
        $selected_item = $this->search_results[$this->highlighted_index] ?? null;
        if ($selected_item) {
            $this->selected_item_id = $selected_item->id;
            $this->search_query = $selected_item->{$this->search_field};
            $this->search_results = [];
        }
    }

    public function render()
    {
        return view('livewire.search-input');
    }
}
