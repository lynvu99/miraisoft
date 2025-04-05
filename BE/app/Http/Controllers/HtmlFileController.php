<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class HtmlFileController extends Controller
{
    /**
     * Retrieve an HTML file based on system, env, and machine number, and return its content in base64.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHtmlFile(Request $request)
    {
        try {
            // Validate the request parameters
            $validated = $request->validate([
                'system' => 'required|string|max:255',
                'env' => 'required|string|max:255',
                'machine_number' => 'required|string|max:255',
                'contract_server' => 'required|string|max:255',
            ]);

            // Extract the validated parameters
            $system = $validated['system'];
            $env = $validated['env'];
            $machineNumber = $validated['machine_number'];
            $contractServer = $validated['contract_server'];

            // Construct the filename (e.g., hbs_0_1.html)
            $filename = "{$system}_{$env}_{$machineNumber}.html";

            // Define the file path (e.g., C:\hbs_0_1.html)
            $filePath = "C:\\{$filename}";

            // Check if the file exists
            if (!file_exists($filePath)) {
                Log::warning('HTML file not found', [
                    'filename' => $filename,
                    'path' => $filePath,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'INFO in response false',
                ], 404);
            }

            // Read the file content
            $htmlContent = file_get_contents($filePath);
            if ($htmlContent === false) {
                throw new Exception('Failed to read the HTML file');
            }

            // Encode the HTML content in base64
            $base64Content = base64_encode($htmlContent);

            // Log the success
            Log::info('HTML file retrieved and encoded successfully', [
                'filename' => $filename,
                'path' => $filePath,
                'contract_server' => $contractServer,
            ]);

            // Return the success response
            return response()->json([
                'success' => true,
                'filename' => $filename,
                'content' => $base64Content,
                'message' => 'INFO in response successfully',
            ], 200);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error retrieving HTML file', [
                'error' => $e->getMessage(),
                'system' => $request->input('system'),
                'env' => $request->input('env'),
                'machine_number' => $request->input('machine_number'),
                'contract_server' => $request->input('contract_server'),
            ]);

            // Return the failure response
            return response()->json([
                'success' => false,
                'message' => 'INFO in response false',
            ], 500);
        }
    }
}