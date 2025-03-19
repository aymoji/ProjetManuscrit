<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class HomeController extends Controller
{
    /**
     * Save drawing and uploaded file data.
     
    public function saveDrawing(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'text' => 'required|string',
            'drawing' => 'required|string', // Ensure drawing data is provided
            'file' => 'nullable|file|mimes:jpeg,png,jpg', // Accept only image files for file upload
        ]);

        try {
            // Initialize variables for file and drawing paths
            $drawingPath = null;
            $filePath = null;
            $fileName = null;
            $fileType = null;

            // Handle the drawing data (base64 string)
            $drawing = $request->input('drawing');
            if ($drawing) {
                // Remove the base64 prefix and decode the image data
                $imageData = str_replace('data:image/png;base64,', '', $drawing);
                $imageData = base64_decode($imageData);

                // Generate a unique name for the image
                $imageName = 'drawing_' . time() . '.png';

                // Save the drawing image in the public storage
                $drawingPath = 'storage/drawings/' . $imageName;
                file_put_contents(storage_path('app/public/drawings/' . $imageName), $imageData);
            }

            // Handle the file upload and save to the `files` table
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Store the file in storage/public/uploads and get its path
                $filePath = $file->store('uploads', 'public');

                // Get the original name and mime type of the file
                $fileName = $file->getClientOriginalName();
                $fileType = $file->getMimeType();

                // Save file details in the `files` table
                File::create([
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'file_type' => $fileType,
                ]);
            }

            // Return success response
            return response()->json([
                'message' => 'Données enregistrées avec succès!',
                'file' => $filePath ?? null, // File path will be null if no file was uploaded
                'image' => $drawingPath ?? null, // Drawing path will be null if no drawing was uploaded
            ], 200);

        } catch (\Exception $e) {
            // Log the error and return an error response
            \Log::error('Erreur dans saveDrawing : ' . $e->getMessage());
            return response()->json([
                'message' => 'Une erreur s\'est produite : ' . $e->getMessage(),
            ], 500);
        }
    }
}*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Save drawing and uploaded file data and process with the model.
     */
    public function saveDrawing(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'text' => 'required|string',
            'drawing' => 'nullable|string', // Drawing can be nullable
            'file' => 'nullable|file|mimes:jpeg,png,jpg', // Accept only image files
        ]);

        try {
            $drawingPath = null;
            $filePath = null;
            $fileName = null;
            $fileType = null;
            $imageData = null;

            // Handle the drawing data (base64 string)
            $drawing = $request->input('drawing');
            if ($drawing) {
                $imageData = str_replace('data:image/png;base64,', '', $drawing);
                $imageData = base64_decode($imageData);

                $imageName = 'drawing_' . time() . '.png';
                $drawingPath = 'storage/drawings/' . $imageName;
                file_put_contents(storage_path('app/public/drawings/' . $imageName), $imageData);
            }

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filePath = $file->store('uploads', 'public');
                $fileName = $file->getClientOriginalName();
                $fileType = $file->getMimeType();

                $imageData = file_get_contents(storage_path('app/public/' . $filePath));

                File::create([
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'file_type' => $fileType,
                ]);
            }

            // Prepare the image for prediction
            if (!$imageData) {
                return response()->json([
                    'message' => 'No drawing or file provided.',
                ], 400);
            }

            // Send the image data to the TensorFlow model API
            $response = Http::post('http://127.0.0.1:5000/predict', [
                'image' => base64_encode($imageData), // Convert image data to base64
            ]);

            // Check for a successful response
            if ($response->successful()) {
                $prediction = $response->json('prediction');
            } else {
                return response()->json([
                    'message' => 'Failed to get a prediction from the model.',
                    'details' => $response->body(),
                ], $response->status());
            }

            // Return success response with prediction and paths
            return response()->json([
                'message' => 'Données enregistrées avec succès!',
                'file' => $filePath ?? null,
                'image' => $drawingPath ?? null,
                'prediction' => $prediction,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Erreur dans saveDrawing : ' . $e->getMessage());
            return response()->json([
                'message' => 'Une erreur s\'est produite : ' . $e->getMessage(),
            ], 500);
        }
    }
}

?>