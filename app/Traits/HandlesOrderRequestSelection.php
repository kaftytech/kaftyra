<?php

namespace App\Traits;

use App\Models\OrderRequest;

trait HandlesOrderRequestSelection
{
    public $highlightIndexOrder = 0;
    public $orders = [];
    public $order_search_by_query_status = '';
    public $order_search_by_query = '';

    public function updatedOrderSearchByQuery()
    {
        $this->order_search_by_query_status = '';
        $this->order_search_by = '';

        $searchWords = explode(' ', $this->order_search_by_query);
        $query = OrderRequest::select('id', 'order_id');

        foreach ($searchWords as $word) {
            $query->where(function ($subquery) use ($word) {
                $subquery->where('order_id', 'like', '%' . $word . '%');
            });
        }

        $this->orders = $query->get();
        $this->highlightIndexOrder = 0;  // Reset to the first item in the list
    }

    // Function to highlight the next customer
    public function highlightNextOrder()
    {
        if ($this->highlightIndexOrder < count($this->orders) - 1) {
            $this->highlightIndexOrder++;
        }
    }

    // Function to highlight the previous customer
    public function highlightPreviousOrder()
    {
        if ($this->highlightIndexOrder > 0) {
            $this->highlightIndexOrder--;
        }
    }

    // Function to select a customer from the list
    public function selectHighlightedOrder()
    {
        if (isset($this->orders[$this->highlightIndexOrder])) {
            $this->selectOrderRequest($this->orders[$this->highlightIndexOrder]['id']);
        }
    }

    // Function to select a customer and update the query field
    public function selectOrderRequest($orderRequestId)
    {
        $orderRequest = OrderRequest::find($orderRequestId);
        $this->order_search_by_query = $orderRequest->order_id;
        $this->orders = [];  // Clear the customer list once selected
        $this->order_search_by_query_status = 'selected';
        $this->order_id = $orderRequest->id;
        $this->updateOrderRequestId($orderRequest->id);
    }
}
