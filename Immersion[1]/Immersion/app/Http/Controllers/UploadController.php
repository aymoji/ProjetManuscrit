<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use App\Models\Drawing;
use App\Models\Upload;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'file' => 'nullable|file|mimes:jpeg,png,jpg',
            'drawing' => 'nullable|string',  // Base64-encoded string
        ]);

        try {
            // Initialize response data
            $filePath = null;
            $drawingPath = null;
            $drawingId = null;
            $fileId = null;

            // Handle file upload if exists
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                // Save file metadata in the database
                $fileRecord = File::create([
                    //'user_id' => auth()->id(),  // Assuming user is authenticated
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'file_type' => $file->getMimeType(),
                ]);

                $fileId = $fileRecord->id;
            }

            // Handle drawing upload if exists
            if ($request->has('drawing')) {
                $drawingData = explode(',', $request->drawing)[1];  // Remove base64 prefix
                $drawingName = 'drawing_' . time() . '.png';
                $drawingPath = 'storage/drawings/' . $drawingName;

                // Store drawing image in public storage
                Storage::disk('public')->put('drawings/' . $drawingName, base64_decode($drawingData));

                // Save drawing metadata in the database
                $drawingRecord = Drawing::create([
                    'user_id' => auth()->id(),
                    'drawing_path' => $drawingPath,
                ]);

                $drawingId = $drawingRecord->id;
            }

            // Save upload record to link user, file, and drawing
            $upload = Upload::create([
                'user_id' => auth()->id(),
                'file_id' => $fileId ?? null,  // Make file_id null if no file was uploaded
                'drawing_id' => $drawingId ?? null,  // Make drawing_id null if no drawing was uploaded
            ]);

            return response()->json([
                'message' => 'Upload successful!',
                'file' => $filePath ?? null,
                'drawing' => $drawingPath ?? null,
                'upload_id' => $upload->id,  // Return the upload record ID
            ], 200);

        } catch (\Exception $e) {
            // Log the error and return an error response
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
?>