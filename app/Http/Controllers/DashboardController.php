<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Employee;
use App\Models\Purchase;
use App\Models\Producttype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Employee Statistics
        $employees = Employee::where('status', 1);
        $totalEmployees = $employees->count();

        // Purchase Statistics
        $purchase = Purchase::select('*')
            ->whereMonth('purchase_date', Carbon::now()->month)
            ->count();

        $recentPurchases = Purchase::with('supplier')
            ->orderBy('purchase_date', 'desc')
            ->limit(5)
            ->get();

        // Stock Statistics
        $total = Stock::count();
        $total_laptop = Stock::where('producttype_id', 1)->count();
        $total_mobile = Stock::where('producttype_id', 19)->count();

        $assigned_laptop = Stock::where('producttype_id', 1)->where('is_assigned', 1)->count();
        $assigned_mobile = Stock::where('producttype_id', 19)->where('is_assigned', 1)->count();
        $total_assigned = Stock::where('is_assigned', 1)->count();
        $total_available = Stock::where('is_assigned', 2)->count();

        // Calculate utilization rate
        $utilizationRate = $total > 0 ? round(($total_assigned / $total) * 100, 1) : 0;

        // Warranty expiring products (within 30 days)
        $warrantyExpiring = Stock::whereNotNull('expired_date')
            ->whereRaw('DATEDIFF(expired_date, CURDATE()) BETWEEN 0 AND 30')
            ->count();

        // Warranty expired products
        $warrantyExpired = Stock::whereNotNull('expired_date')
            ->whereRaw('expired_date < CURDATE()')
            ->count();

        // Low stock items (product types with less than 5 available)
        $lowStockCategories = Producttype::withCount(['stocks as available_count' => function($query) {
            $query->where('is_assigned', 2);
        }])
        ->having('available_count', '<', 5)
        ->having('available_count', '>', 0)
        ->count();

        // Out of stock categories
        $outOfStock = Producttype::withCount(['stocks as available_count' => function($query) {
            $query->where('is_assigned', 2);
        }])
        ->having('available_count', '=', 0)
        ->count();

        // Top 5 product categories by quantity
        $topCategories = Producttype::withCount('stocks')
            ->orderBy('stocks_count', 'desc')
            ->limit(5)
            ->get();

        // Monthly purchase trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyTrend[] = [
                'month' => $date->format('M Y'),
                'count' => Purchase::whereYear('purchase_date', $date->year)
                    ->whereMonth('purchase_date', $date->month)
                    ->count()
            ];
        }

        // Asset condition breakdown
        $conditionStats = Stock::select('asset_condition', DB::raw('count(*) as count'))
            ->whereNotNull('asset_condition')
            ->where('asset_condition', '!=', '')
            ->groupBy('asset_condition')
            ->get();

        // If no condition data exists, create sample data
        if ($conditionStats->isEmpty()) {
            $conditionStats = collect([
                (object)['asset_condition' => 'Good', 'count' => 0],
                (object)['asset_condition' => 'Fair', 'count' => 0],
            ]);
        }

        // Critical alerts
        $criticalAlerts = [
            'warranty_expiring' => $warrantyExpiring,
            'warranty_expired' => $warrantyExpired,
            'low_stock' => $lowStockCategories,
            'out_of_stock' => $outOfStock,
            'pending_tag' => pending_tag()
        ];

        $expired_product = Stock::whereRaw('DATEDIFF(expired_date, CURDATE()) < 90');

        return view('backend.admin.dashboard')->with(compact(
            'employees',
            'totalEmployees',
            'purchase',
            'recentPurchases',
            'total',
            'total_laptop',
            'total_mobile',
            'assigned_laptop',
            'assigned_mobile',
            'total_assigned',
            'total_available',
            'utilizationRate',
            'warrantyExpiring',
            'warrantyExpired',
            'lowStockCategories',
            'outOfStock',
            'topCategories',
            'monthlyTrend',
            'conditionStats',
            'criticalAlerts',
            'expired_product'
        ));
    }
}
