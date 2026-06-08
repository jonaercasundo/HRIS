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
        // Changed email to nullable so HR can delete an incorrect email string entirely
        $request->validate([
            'employee_no' => 'required|string',
            'email'       => 'nullable|email|max:255'
        ]);

        $employeeNo = $request->input('employee_no');
        $emailValue = $request->input('email');

        // Clean-up handler: If input is cleared out, remove the obsolete record row completely
        if (is_null($emailValue) || trim($emailValue) === '') {
            EmployeeEmail::where('employee_no', $employeeNo)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Corporate identity email successfully cleared from profile records.'
            ]);
        }

        // Upsert record structure securely
        EmployeeEmail::updateOrCreate(
            ['employee_no' => $employeeNo],
            ['email' => trim($emailValue)]
        );

        return response()->json([
            'success' => true,
            'message' => 'Identity verification profile updated successfully.'
        ]);
    }
}