<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect($id = null)
    {

        // $authEmployeeId = Auth::id();

        // $labels = [
        //     Carbon::now()->translatedFormat('F'), // Current month in Arabic
        //     Carbon::now()->subMonth()->translatedFormat('F'), // Previous month in Arabic
        //     Carbon::now()->subMonths(2)->translatedFormat('F'), // Two months ago in Arabic
        // ];

        // $totals = [
        //     $this->getAssistanceTotal(0),
        //     $this->getAssistanceTotal(1),
        //     $this->getAssistanceTotal(2),
        // ];

        // $received = [
        //     $this->getReceivedAssistance($authEmployeeId, 0),
        //     $this->getReceivedAssistance($authEmployeeId, 1),
        //     $this->getReceivedAssistance($authEmployeeId, 2),
        // ];

        // $count = Assistance::whereMonth('date', Carbon::now()->month)
        //     ->whereYear('date', Carbon::now()->year)
        //     ->count();

        // $topEmployees = Distribution::select('user_id', DB::raw('SUM(quantity) as total_assistances'))
        //     ->groupBy('user_id')
        //     ->orderByDesc('total_assistances')
        //     ->take(5)
        //     ->get();

        // $fiveDonors = Donor::take(5)->get();

        // $donorContributions = DB::table('properties')
        //     ->join('donors', 'properties.donor_id', '=', 'donors.id')
        //     ->select('donors.name', DB::raw('COUNT(properties.id) as assistance_count'))
        //     ->groupBy('donors.name')
        //     ->orderBy('assistance_count', 'desc') // Sort by count descending
        //     ->get();

        // $topDonors = $donorContributions->take(3);

        // $otherAssistances = $donorContributions->slice(3)->sum('assistance_count'); // Sum the rest

        // // Prepare data for the chart
        // $chartData = [
        //     'labels' => $topDonors->pluck('name')->toArray(),
        //     'data' => $topDonors->pluck('assistance_count')->toArray(),
        // ];

        // // Add "Other" if applicable
        // if ($otherAssistances > 0) {
        //     $chartData['labels'][] = 'الجهات المانحة الأخرى';
        //     $chartData['data'][] = $otherAssistances;
        // }

        // // Convert to JSON for use in JavaScript
        // $chartDataJson = $chartData;

        // dd($chartDataJson);

        if ($id == 'home') {

            return view('dashboard.home');
        }

        if (view()->exists($id)) {
            return view('dashboard.' . $id);
        }

        if ($id == null) {
            return view('dashboard.home');
        } else {
            return view('dashboard.404');
        }
    }

    private function getAssistanceTotal($monthOffset)
    {
        $month =  now()->month;

        $year = now()->year;

        if ($month == 1) {
            if ($monthOffset == 1 || $monthOffset == 2) {
                $year = $year - 1;
            }
        }

        if ($month == 2) {
            if ($monthOffset == 2) {
                $year = $year - 1;
            }
        }


        return DB::table('properties')
            ->whereMonth('date', now()->subMonths($monthOffset)->month)
            ->whereYear('date', $year)
            ->count();
    }

    private function getReceivedAssistance($employeeId, $monthOffset)
    {
        $month =  now()->month;

        $year = now()->year;

        if ($month == 1) {
            if ($monthOffset == 1 || $monthOffset == 2) {
                $year = $year - 1;
            }
        }

        if ($month == 2) {
            if ($monthOffset == 2) {
                $year = $year - 1;
            }
        }

        return DB::table('distributions')
            ->join('properties', 'distributions.assistance_id', '=', 'properties.id')
            ->where('user_id', $employeeId)
            ->whereMonth('properties.date', now()->subMonths($monthOffset)->month)
            ->whereYear('date', $year)
            ->count(); // Or count if it's based on records
    }

    public function guest($id = null)
    {
        return redirect()->route('login');
    }
}
