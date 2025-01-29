<?php
// app/Http/Controllers/API/TransactionController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    // Get all transactions
    public function index()
    {
        $transactions = Transaction::with('ticket')->get();
        return response()->json($transactions);
    }

    // Create a new transaction
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'ticketId' => 'required|uuid|exists:tickets,id',
            'customer_name' => 'required|string',
            'whatsapp_number' => 'required|string',
            'ticket_type' => 'required|in:regular,silver,gold,platinum',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'sometimes|in:pending,success,cancel',
            'data' => 'nullable|json',
            'ticket_number' => 'required|string|unique:transactions,ticket_number',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create transaction
        $transaction = Transaction::create($validator->validated());

        return response()->json($transaction, 201);
    }

    // Get a single transaction by ID
    public function show($id)
    {
        $transaction = Transaction::with('ticket')->findOrFail($id);
        return response()->json($transaction);
    }

    // Update a transaction
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'ticketId' => 'sometimes|uuid|exists:tickets,id',
            'customer_name' => 'sometimes|string',
            'whatsapp_number' => 'sometimes|string',
            'ticket_type' => 'sometimes|in:regular,silver,gold,platinum',
            'quantity' => 'sometimes|integer|min:1',
            'used' => 'sometimes|integer|min:0',
            'total_price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,success,cancel',
            'data' => 'nullable|json',
            'ticket_number' => 'sometimes|string|unique:transactions,ticket_number,' . $id,
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update transaction
        $transaction = Transaction::findOrFail($id);
        $transaction->update($validator->validated());

        return response()->json($transaction);
    }

    // Delete a transaction
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(null, 204);
    }
}
