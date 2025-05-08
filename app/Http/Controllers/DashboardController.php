<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\AuditLog;
use App\Models\Customers;
use App\Models\Leads;
use App\Models\OrderRequest;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function getCategorySales(Request $request)
    {
        $days = $request->get('days', 30); // default to 30 days

        $sales = DB::table('invoice_items')
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('invoice_items.created_at', '>=', now()->subDays($days))
            ->whereNull('invoice_items.deleted_at') // if you're using soft deletes
            ->select('categories.name as label', DB::raw('SUM(invoice_items.net_total) as value'))
            ->groupBy('categories.name')
            ->get();
            
        return response()->json($sales);
    }
    public function getRevenueData(Request $request)
    {
        $period = $request->get('period', 'weekly'); // 'daily', 'weekly', or 'monthly'

        $query = DB::table('transactions')
            ->where('type', 'credit'); // Only credit transactions for revenue calculation

        switch ($period) {
            case 'daily':
                $data = $query
                    ->whereDate('date', today())
                    ->select(DB::raw("HOUR(created_at) as label"), DB::raw("SUM(amount) as value"))
                    ->groupBy(DB::raw("HOUR(created_at)"))
                    ->orderBy('label')
                    ->get();
                break;

            case 'monthly':
                $data = $query
                    ->where('date', '>=', now()->subDays(30))
                    ->select(DB::raw("DATE_FORMAT(date, '%d %b') as label"), DB::raw("SUM(amount) as value"))
                    ->groupBy('label')
                    ->orderBy(DB::raw("STR_TO_DATE(label, '%d %b')"))
                    ->get();
                break;

            default: // weekly
                $data = $query
                    ->where('date', '>=', now()->subDays(7))
                    ->select(DB::raw("DATE_FORMAT(date, '%a') as label"), DB::raw("SUM(amount) as value"))
                    ->groupBy('label')
                    ->orderBy(DB::raw("FIELD(label, 'Mon','Tue','Wed','Thu','Fri','Sat','Sun')"))
                    ->get();
                break;
        }

        return response()->json($data);
    }

    public function getRecentActivities()
    {
        // Fetch the most recent audit logs (for example, the latest 5)
        $activities = AuditLog::with('auditlogable') // Load the related model using morph relation
            ->latest() // Order by created_at descending
            ->take(5) // Limit to 5 most recent logs
            ->get();
        // Format the results for the view
        $formattedActivities = $activities->map(function ($activity) {
            // You can customize how the activity is represented
            return [
                'activity_type' => $activity->event, // Event name (e.g., "Updated record", "Deleted record")
                'description' => $this->getActivityDescription($activity), // Description based on the event
                'created_at' => $activity->created_at->diffForHumans(), // Formatted time (e.g., "2 hours ago")
                'icon_class' => $this->getIconClass($activity->event), // Assign an icon class based on the event type
                'icon' => $this->getIcon($activity->event), // Assign the icon for the event type
            ];
        });

        return response()->json($formattedActivities);
    }

    private function getActivityDescription($activity)
    {
        // You can customize this function to return specific descriptions based on the event type
        return $activity->comments ?: 'No additional comments provided.';
    }

    private function getIconClass($event)
    {
        // Return an icon class based on the event type (you can expand this as needed)
        switch ($event) {
            case 'Created':
                return 'bg-blue-100';
            case 'Updated':
                return 'bg-green-100';
            case 'Deleted':
                return 'bg-red-100';
            default:
                return 'bg-gray-100';
        }
    }

    private function getIcon($event)
    {
        // Return a font-awesome icon based on the event type
        switch ($event) {
            case 'Created':
                return 'fas fa-plus-circle';
            case 'Updated':
                return 'fas fa-edit';
            case 'Deleted':
                return 'fas fa-trash';
            default:
                return 'fas fa-question-circle';
        }
    }

    public function getStats()
    {
        // Sample query to get stats (adjust as per your database schema)
        $totalCustomers = Customers::count(); // Get total number of users (or customers)
        $activeLeads = Leads::where('status', 'active')->count(); // Get active leads
        $revenue = Invoice::where('type', 'locked')->sum('paid_amount'); // Get total revenue
        $pendingOrders = OrderRequest::where('status', 'pending')->count(); // Get pending orders

        return response()->json([
            'totalCustomers' => $totalCustomers,
            'activeLeads' => $activeLeads,
            'revenue' => $revenue,
            'pendingOrders' => $pendingOrders,
        ]);
    }



}
