<?php

namespace App\Http\Controllers;

use App\Models\MainSPPD;
use App\Models\SPPDBellow;
use App\Models\Views;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $startOfMonth = Carbon::now()->startOfMonth(); // Start of the current month
        $today = Carbon::now(); // Current date

        // Fetch daily viewable data for the current month
        $dailyViews = Views::selectRaw('DATE(created_at) as date, SUM(viewable) as total')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Fetch SPPD data for the current month (daily)
        $sppdData = MainSPPD::selectRaw('DATE(created_at) as date, COUNT(id) as total')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $sppds = MainSPPD::with('user')->whereMonth('created_at', $currentMonth)->get();

        $bellow = SPPDBellow::orderByDesc('updated_at')->get(); // Ambil semua data yang sudah terurut dari database
        $latestBellow = $bellow->groupBy('code_sppd')->map(function ($items) {
            return $items->first(); // Karena sudah diurutkan di query, cukup ambil yang pertama
        });

        // Prepare data for the chart
        $dates = []; // X-axis labels (dates of the current month)
        $dataViews = []; // Viewable counts per day
        $dataSppd = []; // SPPD counts per day

        // Create a map for dailyViews and sppdData by date for easier lookup
        $dailyViewsMap = $dailyViews->keyBy('date');
        $sppdDataMap = $sppdData->keyBy('date');

        while ($startOfMonth->lte($today)) {
            // Add the date in 'd M' format
            $dates[] = $startOfMonth->format('d M');

            // Check if there's viewable data for this date, if not, add 0
            $dataViews[] = isset($dailyViewsMap[$startOfMonth->format('Y-m-d')])
                ? $dailyViewsMap[$startOfMonth->format('Y-m-d')]->total
                : 0;

            // Check if there's SPPD data for this date, if not, add 0
            $dataSppd[] = isset($sppdDataMap[$startOfMonth->format('Y-m-d')])
                ? $sppdDataMap[$startOfMonth->format('Y-m-d')]->total
                : 0;

            $startOfMonth->addDay(); // Move to the next day
        }

        // dd($dates);

        return view('dashboard', compact('dates', 'dataViews', 'dataSppd', 'sppds', 'latestBellow'));
    }
}
