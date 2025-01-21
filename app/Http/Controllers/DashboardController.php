<?php

namespace App\Http\Controllers;

use App\Models\Views;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Retrieve the 'viewable' values for the current week
        $monthlyViews = Views::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(viewable) as total')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('year', 'month')
        ->get();
        $labelsMonthly = [];
        $dataMonthly = [];

        foreach ($monthlyViews as $view) {
            $labelsMonthly[] = Carbon::create()->month($view->month)->format('F');
            $dataMonthly[] = $view->total;
        }
        return view('dashboard', compact('labelsMonthly','dataMonthly'));
    }
}
