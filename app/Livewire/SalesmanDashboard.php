<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OrderRequest;
use App\Models\OrderItem;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Account;
use App\Models\AccountTransactions;
use App\Models\Transaction;
use AppModels\User;
use DB;

class SalesmanDashboard extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $currentTab = 'dashboard';
    public $invoiceDetails = null;
    public $orderDetails = null;
    public $selectedOrder = null;
    public $products = [];
    public $searchQuery = '';
    public $customerId = '';
    public $productId = '';
    public $quantity = 1;
    public $orderItems = [];
    public $amount = 0;
    public $paymentMethod = 'cash';
    public $orderNotes;
    public $todayLineProducts = [];
    public $showCustomerModal = false;
    public $selectedProduct = null;
    public $customerQuantities = [];
    public $signatureData;
    public $requireSignature = true; // Set to false if signature is optional

    protected $listeners = ['refreshComponent' => '$refresh'];
    protected $queryString = ['searchQuery' => ['except' => '']];

    public function mount()
    {
        // Initialize with dashboard data
        $this->loadDashboardData();
        $this->loadTodayLineProducts();
    }
    
    public function render()
    {
        return view('livewire.salesman-dashboard', [
            'dashboardData' => $this->getDashboardData(),
            'assignedOrders' => $this->getAssignedOrders(),
            'allOrders' => $this->getAllOrders(),
            'stockProducts' => $this->getStockProducts(),
            'customers' => $this->getCustomers(),
            'productOptions' => $this->getProductOptions(),
        ])->layout('layouts.salesman');;
    }
    
    // Navigation methods
    public function switchTab($tab)
    {
        $this->currentTab = $tab;
        $this->resetPage();
        
        // Load specific data when switching tabs
        if ($tab === 'stock') {
            $this->searchQuery = '';
            $this->getStockProducts();
        }
        if($tab = 'todayLineProducts')
        {
            $this->loadTodayLineProducts();
        }
    }
    public function showCustomerQuantities($productId)
    {
        $this->selectedProduct = Product::find($productId);
        
        $this->customerQuantities = InvoiceItem::whereHas('invoice', function($query) {
                $query->WhereHas('delivery', function($q){                
                    $q->where('assigned_to', auth()->user()->id)
                            ->where('status', '!=' ,'delivered');
                });
            })->where('product_id', $productId)
            ->with(['invoice.customer'])
            ->selectRaw('invoice_id, customer_id, customers.customer_name as customer_name, SUM(quantity) as total_quantity')
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->groupBy('invoice_id', 'customer_id', 'customers.customer_name')
            ->get()
            ->toArray();
        
        $this->showCustomerModal = true;
    }

    public function closeCustomerModal()
    {
        $this->showCustomerModal = false;
        $this->selectedProduct = null;
        $this->customerQuantities = [];
    }
    // Dashboard methods
    public function loadDashboardData()
    {
        // You can either call your API or use direct DB queries here
        $this->dispatch('refreshComponent');
    }
    public function loadTodayLineProducts()
{
    $this->todayLineProducts = InvoiceItem::whereHas('invoice', function($query) {
            $query->WhereHas('delivery', function($q){                
                  $q->where('assigned_to', auth()->user()->id)
                        ->where('status', '!=' ,'delivered');
            });
        })
        ->selectRaw('product_id, products.name, products.product_code, SUM(quantity) as total_quantity')
        ->join('products', 'products.id', '=', 'invoice_items.product_id')
        ->groupBy('product_id', 'products.name', 'products.product_code')
        ->orderBy('total_quantity', 'desc')
        ->get()
        ->toArray();
}
    public function getDashboardData()
    {
        // Replace with your actual data fetching logic
        return [
            'assigned_orders' => Delivery::where('assigned_to', Auth::id())
                ->where('status', '!=', 'delivered')
                ->count(),
            'pending_orders' => OrderRequest::where('created_by', Auth::id())
                ->where('status', 'pending')
                ->count(),
        ];
    }
    
    // Order methods
    public function viewInvoice($invoiceId)
    {
        $this->invoiceDetails = Invoice::with(['customer', 'items.product'])
            ->findOrFail($invoiceId);
        $this->currentTab = 'invoice';
    }
    public function showOrder($orderId)
    {
        $this->orderDetails = OrderRequest::with(['customer', 'orderItems.product'])
            ->findOrFail($orderId);
        $this->currentTab = 'order-show';
    }
   public function markAsDelivered()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            $invoice = $this->invoiceDetails;
            $amount = $this->amount;
            $user = auth()->user();

            $delivery = Delivery::where('invoice_id', $invoice->id)
                ->where('assigned_to', $user->id)
                ->firstOrFail();

            $delivery->update([
                'status' => 'delivered',
                'delivered_date' => now(),
                'delivered_by' => $user->id,
            ]);

            // Update invoice payment status
            $newPaidAmount = $invoice->paid_amount + $amount;
            $newDueAmount = max(0, $invoice->due_amount - $amount);
            $status = $newDueAmount <= 0 ? 'paid' : 'partial';

            $payment = $invoice->payments()->create([
                'amount' =>  $amount,
                'payment_date' => now()->format('Y-m-d'),
                'payment_method' => $this->paymentMethod, // or dynamic if you want
                'status' => 'paid',
                'transaction_id' => '',
                'created_by' => auth()->id(),
                'branch_id' => auth()->user()->currentBranch->id,
            ]);
    
            $lastTransaction = Transaction::latest()->first();
            $opening = $lastTransaction ? $lastTransaction->closing_balance : 0;
            $closing = $opening + $amount;
    
            $invoice->transaction()->create([
                'date' => now()->format('Y-m-d'),
                'description' => 'Payment for Invoice #' . $invoice->id,
                'type' => 'credit',
                'amount' => $amount,
                'opening_balance' => $opening,
                'closing_balance' => $closing,
                'branch_id' => auth()->user()->currentBranch->id,
            ]);

            $invoice->update([
                'paid_amount' => $newPaidAmount,
                'due_amount' => $newDueAmount,
                'status' => $status,
                'payment_method' => $this->paymentMethod ?? 'cash', // fallback if Livewire prop
            ]);

            // Get or create salesman's account
            $account = Account::firstOrCreate(
                ['accountable_id' => $user->id, 'accountable_type' => User::class],
                ['balance' => 0, 'branch_id' => $invoice->branch_id]
            );

            // Record transaction
            $openingBalance = $account->balance;
            $closingBalance = $openingBalance + $amount;

            AccountTransactions::create([
                'account_id' => $account->id,
                'date' => now(),
                'description' => 'Payment for Invoice #' . $invoice->invoice_number,
                'type' => 'credit',
                'amount' => $amount,
                'opening_balance' => $openingBalance,
                'closing_balance' => $closingBalance,
                'txn_mode' => $this->paymentMethod ?? 'cash',
                'branch_id' => $invoice->branch_id,
            ]);

            $account->update(['balance' => $closingBalance]);

            DB::commit();

            session()->flash('message', 'Order marked as delivered successfully');
            $this->switchTab('assigned-orders');

        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();
            logger()->error('Delivery Error:', ['error' => $e->getMessage()]);
            session()->flash('error', 'Something went wrong while marking as delivered.');
        }
    }
    
    public function getAssignedOrders()
    {
        return Delivery::with(['invoice.customer'])
            ->where('assigned_to', Auth::id())
            ->where('status', '!=', 'delivered')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
    
    public function getAllOrders()
    {
        return OrderRequest::with(['customer'])
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
    
    // Stock methods
    public function updatedSearchQuery()
    {
        $this->resetPage(); // Reset pagination when searching
    }

    public function getStockProducts()
    {
        return Product::query()
            ->when($this->searchQuery, function ($query) {
                return $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->searchQuery.'%')
                    ->orWhere('hsn_code', 'like', '%'.$this->searchQuery.'%')
                    ->orWhere('product_code', 'like', '%'.$this->searchQuery.'%');
                });
            })
            ->orderBy('name')
            ->paginate(10);
    }
    
    // New order methods
    public function addOrderItem()
    {
        $this->validate([
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Product::find($this->productId);
        // dd($product);
        
        $this->orderItems[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'quantity' => $this->quantity,
            'price' => $product->selling_price,
        ];
        
        $this->reset(['productId', 'quantity']);
    }
    
    public function removeOrderItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
    }
    
    public function submitOrder()
    {
        $this->validate([
            'customerId' => 'required|exists:customers,id',
            'orderItems' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $order = OrderRequest::create([
                'customer_id' => $this->customerId,
                'request_date' => now(),
                'status' => 'pending',
                'notes' => $this->orderNotes,
                'branch_id' => auth()->user()->currentBranch->id,
                'created_by' => auth()->user()->id,
            ]);

            foreach ($this->orderItems as $item) {
                OrderItem::create([
                    'order_request_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            session()->flash('message', 'Order created successfully');
            $this->reset(['customerId', 'orderItems']);
            $this->switchTab('orders');

        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error('Order submission failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Something went wrong while submitting the order.');
        }
    }
    
    // Helper methods
    public function getCustomers()
    {
        return Customers::orderBy('customer_name')->get();
    }
    public function searchCustomers($search)
    {
        return Customers::where('customer_name', 'like', '%'.$search.'%')
            ->limit(20)
            ->get()
            ->toArray();
    }
    public function searchProducts($search)
    {
        return Product::where('name', 'like', '%'.$search.'%')
            ->limit(20)
            ->get()
            ->toArray();
    }
    public function getProductOptions()
    {
        return Product::orderBy('name')->get();
    }
    
    public function calculateTotal()
    {
        return array_reduce($this->orderItems, function($carry, $item) {
            return $carry + ($item['quantity'] * $item['price']);
        }, 0);
    }
}