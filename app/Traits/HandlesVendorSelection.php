<?php

namespace App\Traits;

use App\Models\Vendors;

trait HandlesVendorSelection
{
    public $search_by_query;
    public $search_by;
    public $search_by_query_status = '';
    public $highlightIndex = 0;
    // Function to handle query updates
    public function updatedSearchByQuery()
    {
        $this->search_by_query_status = '';
        $this->search_by = '';

        $searchWords = explode(' ', $this->search_by_query);
        $query = Vendors::select('id', 'company_name', 'email', 'phone');

        foreach ($searchWords as $word) {
            $query->where(function ($subquery) use ($word) {
                $subquery->where('company_name', 'like', '%' . $word . '%')
                         ->orWhere('email', 'like', '%' . $word . '%')
                         ->orWhere('phone', 'like', '%' . $word . '%');
            });
        }

        $this->vendors = $query->get();
        $this->highlightIndex = 0;  // Reset to the first item in the list
    }

    // Function to highlight the next customer
    public function highlightNext()
    {
        if ($this->highlightIndex < count($this->customers) - 1) {
            $this->highlightIndex++;
        }
    }

    // Function to highlight the previous customer
    public function highlightPrevious()
    {
        if ($this->highlightIndex > 0) {
            $this->highlightIndex--;
        }
    }

    // Function to select a customer from the list
    public function selectHighlighted()
    {
        if (isset($this->vendors[$this->highlightIndex])) {
            $this->sbSelect($this->vendors[$this->highlightIndex]['id']);
        }
    }

    // Function to select a customer and update the query field
    public function sbSelect($customerId)
    {
        $vendor = Vendors::find($customerId);
        $this->search_by_query = $vendor->company_name . ' | ' . $vendor->phone;
        $this->vendors = [];  // Clear the customer list once selected
        $this->search_by_query_status = 'selected';
        $this->selectedVendor = $vendor->id;
    }
}
