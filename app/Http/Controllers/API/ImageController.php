<?php
// app/Http/Controllers/API/ImageController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    // Get all images
    public function index()
    {
        $images = Image::all();
        return response()->json($images);
    }

    // Create a new image
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'filename' => 'required|string',
            'path' => 'required|string',
            'mime_type' => 'required|string',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create image
        $image = Image::create($validator->validated());

        return response()->json($image, 201);
    }

    // Get a single image by ID
    public function show($id)
    {
        $image = Image::findOrFail($id);
        return response()->json($image);
    }

    // Update an image
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'filename' => 'sometimes|string',
            'path' => 'sometimes|string',
            'mime_type' => 'sometimes|string',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update image
        $image = Image::findOrFail($id);
        $image->update($validator->validated());

        return response()->json($image);
    }

    // Delete an image
    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        $image->delete();

        return response()->json(null, 204);
    }
}
