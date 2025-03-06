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
    public function index(Request $request)
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

        // Determine user access
        $isAdmin = Auth::user()->role_id == 2 || in_array(Auth::user()->name, ['SULASNI', 'PARNO', 'DIREKTUR', 'DIREKTUR UTAMA', 'admin']);

        // Fetch only necessary SPPD details
        $sppds = MainSPPD::with('user')->select('id', 'user_id', 'code_sppd', 'maksud_perjalanan', 'lama_perjalanan','date_time_berangkat', 'date_time_kembali', 'verify')
            ->when(!$isAdmin, function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->orderByDesc('created_at', 'desc')
            ->whereMonth('created_at', $currentMonth)
            ->paginate(5);
        // dd($sppds);
        // Get latest SPPDBellow data (only necessary fields)
        $latestBellow = SPPDBellow::select('id', 'code_sppd', 'date_time_arrive', 'arrive_at', 'continue')
            ->orderByDesc('updated_at')
            ->latest()
            ->get()
            ->groupBy('code_sppd');
            // ->map(fn($items) => $items->first());
            // dd($latestBellow);

        // Prepare data for the chart
        $dates = []; // X-axis labels (dates of the current month)
        $dataViews = []; // Viewable counts per day
        $dataSppd = []; // SPPD counts per day

        // Create maps for quick lookup
        $dailyViewsMap = $dailyViews->keyBy('date');

        while ($startOfMonth->lte($today)) {
            $dateString = $startOfMonth->format('Y-m-d');

            $dates[] = $startOfMonth->format('d M');
            $dataViews[] = $dailyViewsMap[$dateString]->total ?? 0;
            $dataSppd[] = $sppdDataMap[$dateString]->total ?? 0;

            $startOfMonth->addDay();
        }
        // Render partial HTML for Blade
        $htmlContent = view('partials.sppd_partials', compact('dates', 'dataViews', 'dataSppd', 'sppds', 'latestBellow'))->render();
        // dd($latestBellow, $sppds);

        return view('dashboard', compact('dates', 'dataViews', 'sppds', 'dataSppd', 'htmlContent', 'latestBellow'));
    }
}
