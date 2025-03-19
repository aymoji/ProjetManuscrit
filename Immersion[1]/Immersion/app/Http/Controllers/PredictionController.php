<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PredictionController extends Controller
{
    public function sendToApi(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'image' => 'required|array', // Ensure 'image' is an array
        ]);

        // Send the request to the Flask API
        try {
            $response = Http::post('http://127.0.0.1:8000/api/images/', [
                'image' => $validated['image'],
            ]);

            // Check if the response is successful
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'prediction' => $response->json('prediction'),
                ]);
            }

            // Handle API errors
            return response()->json([
                'success' => false,
                'error' => 'Flask API error',
                'details' => $response->body(),
            ], $response->status());
        } catch (\Exception $e) {
            // Handle connection or unexpected errors
            return response()->json([
                'success' => false,
                'error' => 'Could not connect to the Flask API',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
