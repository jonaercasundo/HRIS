<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeEmail;

class Employee201Controller extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $employees = DB::table('e_basicinfo as e')
            ->leftJoin('employee_emails as ee', 'ee.employee_no', '=', 'e.employeeNo')
            ->select(
                'e.employeeNo',
                'e.firstName',
                'e.lastName',
                'ee.email'
            )
            ->when($search, function ($q) use ($search) {
                $q->where('e.employeeNo', 'like', "%$search%")
                  ->orWhere('e.firstName', 'like', "%$search%")
                  ->orWhere('e.lastName', 'like', "%$search%");
            })
            ->paginate(20);

        return view('hr.employee_201', compact('employees', 'search'));
    }
    public function saveEmail(Request $request)
    {
        $request->validate([
            'employee_no' => 'required',
            'email' => 'required|email'
        ]);

        EmployeeEmail::updateOrCreate(
            ['employee_no' => $request->employee_no],
            ['email' => $request->email]
        );

        return response()->json([
            'success' => true,
            'message' => 'Email updated successfully'
        ]);
    }
}
