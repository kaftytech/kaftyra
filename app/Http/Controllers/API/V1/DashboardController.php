<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\OrderRequest;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $assignedOrdersCount = Delivery::where('assigned_to', $user->id)
            ->where('status', '!=', 'delivered')
            ->count();
            
        $pendingOrdersCount = OrderRequest::where('created_by', $user->id)
            ->where('status', 'pending')
            ->count();
            
        return response()->json([
            'stats' => [
                'assigned_orders' => $assignedOrdersCount,
                'pending_orders' => $pendingOrdersCount,
            ]
        ]);
    }
}