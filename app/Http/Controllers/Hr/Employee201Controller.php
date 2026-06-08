<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeEmail;

class Employee201Controller extends Controller
{
    /**
     * Display a paginated listing of company directory entities.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $employees = DB::table('e_basicinfo as e')
            ->leftJoin('employee_emails as ee', 'ee.employee_no', '=', 'e.employeeNo')
            ->select(
                'e.employeeNo',
                'e.firstName',
                'e.lastName',
                'ee.email'
            )
            // Nested logical grouping fixes broken OR logic inside raw pagination stacks
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('e.employeeNo', 'like', "%{$search}%")
                             ->orWhere('e.firstName', 'like', "%{$search}%")
                             ->orWhere('e.lastName', 'like', "%{$search}%")
                             ->orWhere('ee.email', 'like', "%{$search}%");
                });
            })
            ->paginate(20);

        return view('hr.employee_201', compact('employees', 'search'));
    }

    /**
     * Commit or truncate individual verification email parameters.
     */
public function saveEmail(Request $request)
{
    try {

        $request->validate([
            'employee_no' => 'required|string',
            'email'       => 'required|email|max:255'
        ]);

        EmployeeEmail::updateOrCreate(
            ['employee_no' => $request->employee_no],
            ['email' => trim($request->email)]
        );

        return response()->json([
            'success' => true,
            'message' => 'Email saved successfully.'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}
}