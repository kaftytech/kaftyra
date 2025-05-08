<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stock;
use App\Traits\HasAuditLog;

class Product extends Model
{
    use HasAuditLog;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'image',
        'description',
        'price',
        'category_id',
        'product_code',
        'mrp',
        'selling_price',
        'gst_percentage',
        'hsn_code',
        'vendor_id',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
        'unit_id',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function vendors()
    {
        return $this->belongsToMany(Vendors::class, 'product_vendors')
                    ->withPivot('vendor_price')
                    ->withTimestamps();
    }


    public function currentStock($branchId = null)
    {
        // Get the single stock record for this product
        $query = $this->stock();
        
        if ($branchId) {
            $query = $query->where('branch_id', $branchId);
        }
        
        $stockRecord = $query->first();
        return $stockRecord ? $stockRecord->current_stock : 0;
    }
    
    public function stockIn($quantity, $model, $note = null)
    {
        // Create the stock adjustment (in)
        $stockAdjustment = $this->createStockAdjustment($quantity, 'in', $model, $note);
        
        // Update the current stock after the adjustment
        $this->updateCurrentStock($quantity);
        
        return $stockAdjustment;
    }
    
    public function stockOut($quantity, $model, $note = null)
    {
        // Check if we have enough stock
        $currentStock = $this->currentStock();
        if ($currentStock < $quantity) {
            throw new \Exception("Insufficient stock for product {$this->name} (ID: {$this->id}). Available: {$currentStock}, Requested: {$quantity}");
        }
        
        // Create the stock adjustment (out)
        $stockAdjustment = $this->createStockAdjustment($quantity, 'out', $model, $note);
        
        // Update the current stock after the adjustment
        $this->updateCurrentStock(-$quantity);
        
        return $stockAdjustment;
    }
    
    protected function createStockAdjustment($quantity, $type, $model, $note = null)
    {
        \Log::info("Creating stock adjustment", [
            'product_id' => $this->id,
            'quantity' => $quantity,
            'type' => $type,
            'note' => $note
        ]);
        
        return StockAdjustment::create([
            'product_id' => $this->id,
            'quantity' => $quantity,
            'type' => $type,
            'adjustable_id' => $model->id,
            'adjustable_type' => get_class($model),
            'note' => $note,
            'date' => now(),
            'performed_by' => auth()->id(),
            'branch_id' => auth()->user()?->branch_id ?? null,
        ]);
    }
    
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
    
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }
    
    public function updateCurrentStock($change)
    {
        \Log::info("Updating current stock", [
            'product_id' => $this->id,
            'change' => $change
        ]);
        
        $branchId = auth()->user()?->branch_id ?? null;
        
        // Get the current stock record
        $stockRecord = $this->stock()->first();
        
        // Calculate new quantity
        $currentStock = $stockRecord ? $stockRecord->current_stock : 0;
        $newQuantity = $currentStock + $change;
        
        \Log::info("Stock calculation", [
            'current' => $currentStock,
            'change' => $change,
            'new' => $newQuantity
        ]);
        
        // Ensure that newQuantity is valid (e.g., not going negative)
        if ($newQuantity < 0) {
            throw new \Exception("Cannot reduce stock below zero for product {$this->name} (ID: {$this->id})");
        }
        
        // Update or create the stock record
        try {
            $stockData = [
                'current_stock' => $newQuantity,
            ];
            
            \Log::info("Updating stock record", [
                'product_id' => $this->id,
                'data' => $stockData
            ]);
            
            // Use updateOrCreate to update existing record or create a new one
            $updatedStock = Stock::where('product_id', $this->id)->first();
            // dd($newQuantity);
            if (!$updatedStock) {
                $updatedStock = new Stock;
                $updatedStock->product_id = $this->id;
                $updatedStock->current_stock = $newQuantity;
                $updatedStock->save();
            }
            else
            {
                $updatedStock->current_stock = $newQuantity;
                $updatedStock->save();
            }
            // dd($updatedStock);
            \Log::info("Stock record updated", [
                'id' => $updatedStock->id,
                'new_stock' => $updatedStock->current_stock
            ]);
            
            return $newQuantity;
        } catch (\Exception $e) {
            \Log::error("Error updating stock", [
                'product_id' => $this->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
