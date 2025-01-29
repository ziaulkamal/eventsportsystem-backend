<?php
// app/Http/Controllers/API/TicketController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    // Get all tickets
    public function index()
    {
        $tickets = Ticket::all();
        return response()->json($tickets);
    }

    // Create a new ticket
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'regular_quota' => 'required|integer',
            'regular_price' => 'required|integer',
            'silver_quota' => 'nullable|integer',
            'silver_price' => 'nullable|integer',
            'gold_quota' => 'nullable|integer',
            'gold_price' => 'nullable|integer',
            'platinum_quota' => 'nullable|integer',
            'platinum_price' => 'nullable|integer',
            'status' => 'sometimes|in:open,closed',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create ticket
        $ticket = Ticket::create($validator->validated());

        return response()->json($ticket, 201);
    }

    // Get a single ticket by ID
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return response()->json($ticket);
    }

    // Update a ticket
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'regular_quota' => 'sometimes|integer',
            'regular_price' => 'sometimes|integer',
            'silver_quota' => 'nullable|integer',
            'silver_price' => 'nullable|integer',
            'gold_quota' => 'nullable|integer',
            'gold_price' => 'nullable|integer',
            'platinum_quota' => 'nullable|integer',
            'platinum_price' => 'nullable|integer',
            'status' => 'sometimes|in:open,closed',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update ticket
        $ticket = Ticket::findOrFail($id);
        $ticket->update($validator->validated());

        return response()->json($ticket);
    }

    // Delete a ticket
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return response()->json(null, 204);
    }
}
