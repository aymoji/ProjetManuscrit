<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;  // Make sure you have a File model for saving file metadata (optional)

class DrawingController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'text' => 'required|string',
            'drawing' => 'required|string',  // Ensure the drawing is a base64-encoded string
            'file' => 'nullable|file|mimes:jpeg,png,jpg',  // Accept only jpeg, png, jpg file types
        ]);

        try {
            // Handle drawing data (base64 string)
            $drawingPath = null;
            $imageData = explode(',', $request->drawing)[1];
            $imageName = 'drawing_' . time() . '.png';
            $drawingPath = 'storage/drawings/' . $imageName;

            // Store the drawing image in public storage
            Storage::disk('public')->put('drawings/' . $imageName, base64_decode($imageData));

            // Handle file upload (if provided)
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                // Store the uploaded file in public storage
                $filePath = $file->store('uploads', 'public');
                $fileName = $file->getClientOriginalName();
                $fileType = $file->getMimeType();

                // Optional: Save file metadata in the database
                 File::create([
                     'file_path' => $filePath,
                     'file_name' => $fileName,
                     'file_type' => $fileType,
                 ]);
            }

            // Return success response with file and image paths
            return response()->json([
                'message' => 'Données enregistrées avec succès!',
                'image' => $drawingPath,
                'file' => $filePath ?? null,  // Return file path if uploaded
            ], 200);

        } catch (\Exception $e) {
            // Log the error and return an error response
            \Log::error('Erreur dans store : ' . $e->getMessage());
            return response()->json([
                'message' => 'Une erreur s\'est produite : ' . $e->getMessage(),
            ], 500);
        }
    }
}
    <?php*/
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use App\Models\File;
    use App\Models\Drawing;   
    
    class DrawingController extends Controller
    {
        public function store(Request $request)
        {
           
            $request->validate([
                //'email' => 'required|email', 
                'text' => 'required|string',
                'drawing' => 'required|string', 
                'file' => 'nullable|file|mimes:jpeg,png,jpg',  
            ]);
    
            try {
               
                $drawingPath = null;
                    $imageData = explode(',', $request->drawing)[1];
                    $imageName = 'drawing_' . time() . '.png';
                    $drawingPath = 'storage/drawings/' . $imageName;

                    
                    Storage::disk('public')->put('drawings/' . $imageName, base64_decode($imageData));

                    
                    Drawing::create([
                        'user_id' => auth()->id(),  
                        'drawing_path' => $drawingPath,  
                    ]);

    
                
                Storage::disk('public')->put('drawings/' . $imageName, base64_decode($imageData));
    
                
                $filePath = null;
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    // Store the uploaded file in public storage
                    $filePath = $file->store('uploads', 'public');
                    $fileName = $file->getClientOriginalName();
                    $fileType = $file->getMimeType();
    
                   
                    File::create([
                        //'user_id' => auth()->id(),  
                        'file_path' => $filePath,
                        'file_name' => $fileName,
                        'file_type' => $fileType,
                        //'user_id' => auth()->id(),
                    ]);
                }
    
                
                return response()->json([
                    'message' => 'Données enregistrées avec succès!',
                    'image' => $drawingPath,
                    'file' => $filePath ?? null,  
                ], 200);
    
            } catch (\Exception $e) {
                // Log the error and return an error response
                //\Log::error('Erreur dans store : ' . $e->getMessage());
                return response()->json([
                    'message' => 'Une erreur s\'est produite : ' . $e->getMessage(),
                ], 500);
            }
        }
    }
    