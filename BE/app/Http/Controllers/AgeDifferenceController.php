<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class AgeDifferenceController extends Controller
{
    /**
     * Calculate the 20% of people with the largest age difference from the mean age in a large city.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateLargestAgeDifference(Request $request)
    {
        try {
            // Define the population size
            $totalPopulation = 1000000; // 1,000,000 people

            // Assume a normal distribution for ages
            $meanAgeYears = 40; // Mean age: 40 years
            $standardDeviationYears = 15; // Standard deviation: 15 years

            // Convert to months for consistency with previous calculations (optional)
            $meanAgeMonths = $meanAgeYears * 12; // 480 months
            $standardDeviationMonths = $standardDeviationYears * 12; // 180 months

            // Calculate the number of people in the top 20% (10% youngest + 10% oldest)
            $top20PercentCount = $totalPopulation * 0.2; // 200,000 people
            $tailCount = $top20PercentCount / 2; // 100,000 people in each tail (bottom 10% and top 10%)

            // Z-scores for the 10th and 90th percentiles
            $zScore10thPercentile = -1.28; // Bottom 10%
            $zScore90thPercentile = 1.28;  // Top 10%

            // Calculate the age thresholds in years
            $lowerAgeThresholdYears = $meanAgeYears + ($zScore10thPercentile * $standardDeviationYears); // ~20.8 years
            $upperAgeThresholdYears = $meanAgeYears + ($zScore90thPercentile * $standardDeviationYears); // ~59.2 years

            // Convert thresholds to years and months for readability
            $lowerAgeYears = floor($lowerAgeThresholdYears); // 20 years
            $lowerAgeMonths = round(($lowerAgeThresholdYears - $lowerAgeYears) * 12); // ~9.6 months ≈ 10 months

            $upperAgeYears = floor($upperAgeThresholdYears); // 59 years
            $upperAgeMonths = round(($upperAgeThresholdYears - $upperAgeYears) * 12); // ~2.4 months ≈ 2 months

            // Prepare the result
            $result = [
                'total_population' => $totalPopulation,
                'mean_age' => ['years' => $meanAgeYears, 'months' => 0],
                'largest_age_difference_20_percent' => [
                    'count' => (int)$top20PercentCount,
                    'youngest_group' => [
                        'count' => (int)$tailCount,
                        'age_threshold' => [
                            'years' => (int)$lowerAgeYears,
                            'months' => (int)$lowerAgeMonths,
                        ],
                        'description' => "People younger than {$lowerAgeYears} years {$lowerAgeMonths} months",
                    ],
                    'oldest_group' => [
                        'count' => (int)$tailCount,
                        'age_threshold' => [
                            'years' => (int)$upperAgeYears,
                            'months' => (int)$upperAgeMonths,
                        ],
                        'description' => "People older than {$upperAgeYears} years {$upperAgeMonths} months",
                    ],
                ],
            ];

            // Log the calculation
            Log::info('Calculated 20% of people with largest age difference', [
                'total_population' => $totalPopulation,
                'mean_age_years' => $meanAgeYears,
                'standard_deviation_years' => $standardDeviationYears,
                'lower_age_threshold_years' => $lowerAgeThresholdYears,
                'upper_age_threshold_years' => $upperAgeThresholdYears,
                'result' => $result,
            ]);

            // Return the response
            return response()->json([
                'status' => 'success',
                'message' => 'Calculated 20% of people with largest age difference',
                'data' => $result,
            ], 200);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error calculating largest age difference', [
                'error' => $e->getMessage(),
            ]);

            // Return the error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to calculate largest age difference',
            ], 500);
        }
    }
}