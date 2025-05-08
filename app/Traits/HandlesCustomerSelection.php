<?php

namespace App\Traits;

use App\Models\Customers;

trait HandlesCustomerSelection
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
        $query = Customers::select('id', 'customer_name', 'email', 'phone');

        foreach ($searchWords as $word) {
            $query->where(function ($subquery) use ($word) {
                $subquery->where('customer_name', 'like', '%' . $word . '%')
                         ->orWhere('email', 'like', '%' . $word . '%')
                         ->orWhere('phone', 'like', '%' . $word . '%');
            });
        }

        $this->customers = $query->get();
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
        if (isset($this->customers[$this->highlightIndex])) {
            $this->sbSelect($this->customers[$this->highlightIndex]['id']);
        }
    }

    // Function to select a customer and update the query field
    public function sbSelect($customerId)
    {
        $customer = Customers::find($customerId);
        $this->search_by_query = $customer->customer_name . ' | ' . $customer->phone;
        $this->customers = [];  // Clear the customer list once selected
        $this->search_by_query_status = 'selected';
        $this->customer_id = $customer->id;
        $this->customer = $customer;
    }
}
