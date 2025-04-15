<?php

namespace App\Http\Controllers\Dashboard;


use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function import(Request $request)
    {
        // Validate file input
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Import the file
        Excel::import(new UsersImport, $request->file('file'));

        return back()->with('success', 'Users imported successfully!');
    }
}
