<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ScheduleController extends Controller
{
    // Get all schedules
    public function index()
    {
        $schedules = Schedule::with(['venue', 'sportClass', 'sport', 'user'])->get();
        return response()->json($schedules);
    }

    // Create a new schedule
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            // 'start_time' => 'required|date_format:H:i',
            // 'end_time' => 'required|date_format:H:i|after:start_time',
            'venueId' => 'required|uuid|exists:venues,id',
            'sportId' => 'required|uuid|exists:sports,id',
            'sportClassId' => 'required|uuid|exists:sport_classes,id',
            'status' => 'sometimes|in:active,inactive',
            'userId' => 'nullable|uuid|exists:users,id',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create schedule
        $validatedData = $validator->validated();

        // Generate UUID for new schedule
        $validatedData['id'] = Str::uuid(); // Automatically generate UUID

        // Create the schedule in the database
        $schedule = Schedule::create($validatedData);

        return response()->json($schedule, 201);
    }


    // Get a single schedule by ID
    public function show($id)
    {
        $schedule = Schedule::with(['venue', 'sportClass', 'sport', 'user'])->findOrFail($id);
        return response()->json($schedule);
    }

    // Update a schedule
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|date',
            // 'start_time' => 'sometimes|date_format:H:i',
            // 'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'venueId' => 'sometimes|uuid|exists:venues,id',
            'sportId' => 'sometimes|uuid|exists:sports,id',
            'sportClassId' => 'sometimes|uuid|exists:sport_classes,id',
            'status' => 'sometimes|in:active,inactive',
            'userId' => 'nullable|uuid|exists:users,id',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update schedule
        $schedule = Schedule::findOrFail($id);
        $schedule->update($validator->validated());

        return response()->json($schedule);
    }

    // Delete a schedule
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json(null, 204);
    }

    public function getSchedule(Request $request)
    {
        $query = Schedule::with([
            'sport',
            'venue',
            'sportClass',
        ])->orderBy('created_at', 'desc');
        // dd($query);

        // Cek apakah ada parameter pencarian dari DataTables
        if (!empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where('sport.name', 'LIKE', "%{$search}%")
            ->orWhere('sportClass.classOption', 'LIKE', "%{$search}%"); // Filter berdasarkan nama
        }

        // Hitung total data sebelum filtering
        $totalData = $query->count();

        // Ambil data sesuai pagination DataTables
        $schedule = $query->offset($request->start)
        ->limit($request->length)
        ->get();

        // Jika tidak ada data
        if ($schedule->isEmpty()) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }



        // Mapping data
        $filteredData = $schedule->map(function ($schedules)  {


            return [
                'id' => $schedules->id,
                'date' => $schedules->date,
                'venue' => $schedules->venue->name,
                'sportId'  => $schedules->sport->name,
                'sportClassId' => $schedules->sportClass->classOption,
                'gender' => $schedules->sportClass->type,
                'status' =>  $schedules->status,
            ];
        });

        // Return response sesuai format DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData->count(),
            'data' => $filteredData->values()
        ]);
    }
}
