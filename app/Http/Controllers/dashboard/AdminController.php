<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
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

        $admins = User::where('role', '<>', 'user')->count();

        $messages = Contact::where('is_open', 0)->count();

        $categories = DB::select('select categories.name, count(*) as count from categories join properties on properties.category_id = categories.id group by categories.name DESC');

        $labels = [
            Carbon::now()->translatedFormat('F'), // Current month in Arabic
            Carbon::now()->subMonth()->translatedFormat('F'), // Previous month in Arabic
            Carbon::now()->subMonths(2)->translatedFormat('F'), // Two months ago in Arabic
        ];

        $totals = [
            $this->getPropertiesTotal(0),
            $this->getPropertiesTotal(1),
            $this->getPropertiesTotal(2),
        ];

        $count = Property::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();


        $sale = Property::where('type', 'sale')->count();
        $rent = Property::where('type', 'rent')->count();


        // Prepare data for the chart
        $chartData = [
            'labels' => ['بيع', 'تأجير'],
            'data' => [$sale, $rent],
        ];

        // Convert to JSON for use in JavaScript
        $chartDataJson = $chartData;


        if ($id == 'home') {
            return view('dashboard.home', compact('totals', 'labels', 'count', 'admins', 'messages', 'chartDataJson', 'categories'));
        }

        if (view()->exists($id)) {
            return view('dashboard.' . $id);
        }


        if ($id == null) {
            return view('dashboard.home', compact('totals', 'labels', 'count', 'admins', 'messages', 'chartDataJson', 'categories'));
        } else {
            return view('dashboard.404');
        }
    }

    private function getPropertiesTotal($monthOffset)
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
            ->whereMonth('created_at', now()->subMonths($monthOffset)->month)
            ->whereYear('created_at', $year)
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
