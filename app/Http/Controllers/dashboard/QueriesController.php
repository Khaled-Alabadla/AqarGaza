<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Assistance;
use App\Models\Distribution;
use App\Models\Donor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QueriesController extends Controller
{
    public function users()
    {
        Gate::authorize('queries.users');

        $users = User::all();

        $request = request();

        $query = Distribution::query();

        $formattedStartDate = '';

        $formattedEndDate = '';

        // dd($request->all());

        // Get the date input from the request
        if ($request->query('start_date')) {
            $dateInput = trim($request->query('start_date'));
            try {
                $parsedDate = Carbon::createFromFormat('d/m/Y', $dateInput);
            } catch (\Exception $e) {
                return back()->withErrors(['start_date' => 'خطأ في تنسيق التاريخ، الرجاء الضغط على حقل الإدخال ثم الاختيار من التقويم'])->withInput();
            }

            $formattedStartDate = $parsedDate->format('Y-m-d');
        }
        if ($request->query('end_date')) {
            $dateInput = trim($request->query('end_date'));
            try {
                $parsedDate = Carbon::createFromFormat('d/m/Y', $dateInput);
            } catch (\Exception $e) {
                return back()->withErrors(['end_date' => 'خطأ في تنسيق التاريخ، الرجاء الضغط على حقل الإدخال ثم الاختيار من التقويم'])->withInput();
            }

            // After successfully parsing, reformat to MySQL-friendly format Y-m-d
            $formattedEndDate = $parsedDate->format('Y-m-d');
            // dd($formattedEndDate);
        }

        if ($user = $request->query('user')) {
            $query->where('user_id', $user);
        } else {
            $query->where('user_id', 0);
        }

        if ($formattedStartDate) {
            $query->where('created_at', '>=', $formattedStartDate);
        }

        if ($formattedEndDate) {
            $query->where('created_at', '<=', $formattedEndDate . ' 23:59:59');
        }

        $distributes = $query->get();

        return view('queries.users', compact('users', 'distributes'));
    }

    public function donors()
    {
        Gate::authorize('queries.donors');

        $donors = Donor::all();

        $request = request();

        $query = Assistance::query();

        $formattedStartDate = '';

        $formattedEndDate = '';

        if ($request->query('start_date')) {
            $dateInput = trim($request->query('start_date'));
            try {
                $parsedDate = Carbon::createFromFormat('d/m/Y', $dateInput);
            } catch (\Exception $e) {
                return back()->withErrors(['start_date' => 'خطأ في تنسيق التاريخ، الرجاء الضغط على حقل الإدخال ثم الاختيار من التقويم'])->withInput();
            }

            $formattedStartDate = $parsedDate->format('Y-m-d');
        }

        if ($request->query('end_date')) {
            $dateInput = trim($request->query('end_date'));
            try {
                $parsedDate = Carbon::createFromFormat('d/m/Y', $dateInput);
            } catch (\Exception $e) {
                return back()->withErrors(['end_date' => 'خطأ في تنسيق التاريخ، الرجاء الضغط على حقل الإدخال ثم الاختيار من التقويم'])->withInput();
            }

            $formattedEndDate = $parsedDate->format('Y-m-d');
        }

        if ($donor = $request->query('donor')) {
            $query->where('donor_id', $donor);
        } else {
            $query->where('donor_id', 0);
        }

        if ($formattedStartDate) {
            $query->where('date', '>=', $formattedStartDate);
        }

        if ($formattedEndDate) {
            $query->where('date', '<=', $formattedEndDate);
        }

        $properties = $query->get();

        return view('queries.donors', compact('properties', 'donors'));
    }
}
