<?php
// app/Http/Controllers/API/UserController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        $users = User::get();
        return response()->json($users);
    }

    // Create a new user
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'peopleId' => 'required|uuid|exists:people,id|unique:users,peopleId',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'levelId' => 'required|uuid|exists:levels,id',
            'status' => 'sometimes|in:active,pending,banned',
            'activationNumber' => 'nullable|string',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Hash password before saving
        $validatedData = $validator->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create user
        $user = User::create($validatedData);

        return response()->json($user, 201);
    }

    // Get a single user by ID
    public function show($id)
    {
        $user = User::with(['person', 'level'])->findOrFail($id);
        return response()->json($user);
    }

    // Update a user
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'peopleId' => 'sometimes|uuid|exists:people,id|unique:users,peopleId,' . $id,
            'username' => 'sometimes|string|unique:users,username,' . $id,
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'levelId' => 'sometimes|uuid|exists:levels,id',
            'status' => 'sometimes|in:active,pending,banned',
            'activationNumber' => 'nullable|string',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Hash password if provided
        $validatedData = $validator->validated();
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        // Update user
        $user = User::findOrFail($id);
        $user->update($validatedData);

        return response()->json($user);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
