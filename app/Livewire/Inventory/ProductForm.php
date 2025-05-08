<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Product;
use App\Models\Vendors;
use App\Traits\HandlesVendorSelection;
class ProductForm extends Component
{
    use HandlesVendorSelection;
    
    public $productId; // null for create, set for edit
    public $name, $product_code, $description, $mrp, $selling_price, $gst_percentage, $category_id, $unit_id;
    public $vendorPrices = [];
    public $selectedVendor;
    
    public $categories, $units, $vendors;

    public function mount($product = null)
    {
        $this->categories = \App\Models\Category::all();
        $this->units = \App\Models\Unit::all();
        $this->vendors = Vendors::all();

        if ($product) {
            $this->productId = $product->id;
            $this->name = $product->name;
            $this->product_code = $product->product_code;
            $this->description = $product->description;
            $this->mrp = $product->mrp;
            $this->selling_price = $product->selling_price;
            $this->gst_percentage = $product->gst_percentage;
            $this->category_id = $product->category_id;
            $this->unit_id = $product->unit_id;

            // Assuming vendor_prices is a pivot or related model
            foreach ($product->vendors as $vendor) {
                $this->vendorPrices[$vendor->id] = [
                    'name' => $vendor->company_name,
                    'price' => $vendor->pivot->vendor_price ?? null,
                ];
            }
        }
    }

    public function addVendor()
    {
        if ($this->selectedVendor && !isset($this->vendorPrices[$this->selectedVendor])) {
            $vendor = Vendors::find($this->selectedVendor);
            if ($vendor) {
                $this->vendorPrices[$vendor->id] = [
                    'name' => $vendor->company_name,
                    'price' => '',
                ];
            }
            $this->selectedVendor = null;
        }
    }

    public function removeVendor($id)
    {
        unset($this->vendorPrices[$id]);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
        ]);

        $product = Product::updateOrCreate(
            ['id' => $this->productId],
            [
                'name' => $this->name,
                'product_code' => $this->product_code,
                'description' => $this->description,
                'mrp' => $this->mrp,
                'selling_price' => $this->selling_price,
                'gst_percentage' => $this->gst_percentage,
                'category_id' => $this->category_id,
                'unit_id' => $this->unit_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]
        );

        // Sync vendor prices
        $syncData = [];
        foreach ($this->vendorPrices as $vendorId => $data) {
            $syncData[$vendorId] = ['vendor_price' => $data['price']];
        }
        $product->vendors()->sync($syncData);

        session()->flash('message', $this->productId ? 'Product updated!' : 'Product created!');
        return redirect()->route('products.index');
    }
    public function render()
    {
        return view('livewire.inventory.product-form');
    }
}
