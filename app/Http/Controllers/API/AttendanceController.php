<?php
// app/Http/Controllers/API/AttendanceController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    // Get all attendance records
    public function index()
    {
        $attendance = Attendance::with(['transaction', 'user'])->get();
        return response()->json($attendance);
    }

    // Create a new attendance record
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'transactionId' => 'required|uuid|exists:transactions,id',
            'userId' => 'nullable|uuid|exists:users,id',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create attendance record
        $attendance = Attendance::create($validator->validated());

        return response()->json($attendance, 201);
    }

    // Get a single attendance record by ID
    public function show($id)
    {
        $attendance = Attendance::with(['transaction', 'user'])->findOrFail($id);
        return response()->json($attendance);
    }

    // Update an attendance record
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'transactionId' => 'sometimes|uuid|exists:transactions,id',
            'userId' => 'nullable|uuid|exists:users,id',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update attendance record
        $attendance = Attendance::findOrFail($id);
        $attendance->update($validator->validated());

        return response()->json($attendance);
    }

    // Delete an attendance record
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json(null, 204);
    }
}
