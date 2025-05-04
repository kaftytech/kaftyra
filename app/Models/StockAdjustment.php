<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stock;
use Log;
class StockAdjustment extends Model
{
    protected $table = 'stock_adjustments';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendors::class);
    }

    // In Stock Model (Stock.php)
    public static function changeStock($productId, $quantity, $type, $adjustableModel)
    {
        
        $adjustedQuantity = $type === 'in' ? $quantity : -$quantity;

        // Update or create the current stock record
        $stock = Stock::firstOrNew(['product_id' => $productId]);
        $stock->current_stock = ($stock->current_stock ?? 0) + $adjustedQuantity;
        $stock->save();

        Log::info("Adjusting stock", [
            'product_id' => $productId,
            'type' => $type,
            'quantity' => $quantity,
            'adjusted_quantity' => $adjustedQuantity,
            'final_stock' => $stock->current_stock,
        ]);

        // Create a new stock adjustment
        return self::create([
            'product_id'     => $productId,
            'quantity'       => $quantity,
            'type'           => $type, 
            'adjustable_id'   => $adjustableModel->id,
            'adjustable_type' => get_class($adjustableModel),
            'date'           => now(),
            'note'           => 'Stock movement for ' . class_basename($adjustableModel) . ' #' . $adjustableModel->id,
            'performed_by'   => auth()->id(),
        ]);
    }

    public function adjustable()
    {
        return $this->morphTo();
    }

    

}
