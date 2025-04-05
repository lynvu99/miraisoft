<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class FurthestPeopleController extends Controller
{
    /**
     * Calculate the 10% of people who are furthest from the flag lines.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateFurthestPeople(Request $request)
    {
        try {
            // Define the population size and area
            $totalPopulation = 1000000; // 1,000,000 people
            $areaBounds = 2; // Area from x = -2 to x = 2, y = -2 to y = 2
            $area = (2 * $areaBounds) * (2 * $areaBounds); // 16 square units

            // Flag line positions
            $redLineX = 0;   // Red line at x = 0
            $yellowLineY = 0; // Yellow line at y = 0
            $greenLineY = 1;  // Green line at y = 1
            $blueLineX = 1;   // Blue line at x = 1

            // Simulate a sample of positions to estimate the distance distribution
            $sampleSize = 10000; // Use a smaller sample to estimate the 90th percentile
            $distances = [];

            for ($i = 0; $i < $sampleSize; $i++) {
                // Generate random positions in the area [-2, 2] × [-2, 2]
                $x = mt_rand(-2000, 2000) / 1000; // Random x from -2 to 2
                $y = mt_rand(-2000, 2000) / 1000; // Random y from -2 to 2

                // Calculate distance to the nearest vertical line (red or blue)
                $distanceToVertical = min(abs($x - $redLineX), abs($x - $blueLineX));

                // Calculate distance to the nearest horizontal line (yellow or green)
                $distanceToHorizontal = min(abs($y - $yellowLineY), abs($y - $greenLineY));

                // Total distance metric
                $distance = $distanceToVertical + $distanceToHorizontal;

                $distances[] = $distance;
            }

            // Sort distances in ascending order
            sort($distances);

            // Find the 90th percentile (i.e., the distance threshold for the top 10%)
            $index90thPercentile = (int)(0.9 * $sampleSize); // 90% of the sample size
            $distanceThreshold = $distances[$index90thPercentile];

            // Estimate the number of people in the top 10%
            $top10PercentCount = (int)($totalPopulation * 0.1); // 100,000 people

            // Prepare the result
            $result = [
                'total_population' => $totalPopulation,
                'area' => "x = [-{$areaBounds}, {$areaBounds}], y = [-{$areaBounds}, {$areaBounds}]",
                'flag_lines' => [
                    'red' => 'x = 0',
                    'yellow' => 'y = 0',
                    'green' => 'y = 1',
                    'blue' => 'x = 1',
                ],
                'furthest_10_percent' => [
                    'count' => $top10PercentCount,
                    'distance_threshold' => round($distanceThreshold, 2),
                    'description' => "People whose sum of distances to the nearest vertical and horizontal flag lines is greater than " . round($distanceThreshold, 2),
                ],
            ];

            // Log the calculation
            Log::info('Calculated 10% of people furthest from flag lines', [
                'total_population' => $totalPopulation,
                'sample_size' => $sampleSize,
                'distance_threshold' => $distanceThreshold,
                'result' => $result,
            ]);

            // Return the response
            return response()->json([
                'status' => 'success',
                'message' => 'Calculated 10% of people furthest from flag lines',
                'data' => $result,
            ], 200);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error calculating furthest people', [
                'error' => $e->getMessage(),
            ]);

            // Return the error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to calculate furthest people',
            ], 500);
        }
    }
}