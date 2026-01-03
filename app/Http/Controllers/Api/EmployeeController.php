<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Employee::with('department');

            // FILTER: hanya employee yang belum punya kamar aktif
            if ($request->has('available')) {
                $query->whereDoesntHave('occupancies', function ($q) {
                    $q->where('status', 'active');
                });
            }

            return response()->json($query->get());
        } catch (\Exception $e) {
            Log::error('Employee index error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Internal server error',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
        ]);

        // AUTO GENERATE CODE
        $last = Employee::orderByDesc('id')->first();
        $nextNumber = $last ? ((int) substr($last->employee_code, 3)) + 1 : 1;
        $employeeCode = 'EMP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $employee = Employee::create([
            'employee_code' => $employeeCode,
            'name' => $request->name,
            'department_id' => $request->department_id,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Employee created',
            'employee' => $employee->load('department')
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    }
}


    public function update(Request $request, Employee $employee)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'department_id' => 'required|exists:departments,id',
            ]);

            $employee->update([
                'name' => $request->name,
                'department_id' => $request->department_id,
            ]);

            return response()->json([
                'message' => 'Employee updated',
                'employee' => $employee->load('department')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Employee update error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Internal server error',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function toggle(Employee $employee)
    {
        try {
            $employee->update([
                'is_active' => ! $employee->is_active
            ]);

            return response()->json([
                'message' => 'Employee status updated',
                'employee' => $employee->load('department')
            ]);
        } catch (\Exception $e) {
            Log::error('Employee toggle error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Internal server error',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function destroy(Employee $employee)
{
    try {

        // CEK: employee masih punya kamar aktif
        $stillCheckedIn = $employee->occupancies()
            ->whereNull('checked_out_at')
            ->exists();

        if ($stillCheckedIn) {
            return response()->json([
                'message' => 'Employee is still checked in to a room'
            ], 409);
        }

        // AMAN DIHAPUS
        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted'
        ]);

    } catch (\Exception $e) {

        \Log::error('Employee delete error: ' . $e->getMessage());

        return response()->json([
            'message' => 'Internal server error'
        ], 500);
    }
}


}