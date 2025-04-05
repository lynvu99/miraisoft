<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class StudentAgeController extends Controller
{
    /**
     * Calculate the number of students in each class size who are younger than the average age minus 6 months.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateYoungerStudents(Request $request)
    {
        try {
            // Define the class sizes and their counts
            $classes = [
                35 => 5, // 5 classes with 35 students
                45 => 6, // 6 classes with 45 students
                30 => 10, // 10 classes with 30 students
                40 => 4, // 4 classes with 40 students
            ];

            // Calculate total students (for reference)
            $totalStudents = (5 * 35) + (6 * 45) + (10 * 30) + (4 * 40); // 905 students

            // Average age: 20 years 8 months = 248 months
            $averageAgeMonths = (20 * 12) + 8; // 248 months

            // Target age: Average age - 6 months = 242 months
            $targetAgeMonths = $averageAgeMonths - 6; // 242 months

            // Assume a normal distribution with a standard deviation of 12 months
            $standardDeviation = 12;

            // Calculate the Z-score
            $zScore = ($targetAgeMonths - $averageAgeMonths) / $standardDeviation; // -0.5

            // Approximate the proportion of students younger than the target age
            // For Z = -0.5, the cumulative probability is ~0.3085 (using a normal distribution table)
            $proportionYounger = 0.3085; // 30.85%

            // Calculate the number of younger students for each class size
            $result = [];
            foreach ($classes as $classSize => $classCount) {
                $youngerStudentsPerClass = round($classSize * $proportionYounger);
                $result[$classSize] = [
                    'class_size' => $classSize,
                    'number_of_classes' => $classCount,
                    'younger_students_per_class' => (int)$youngerStudentsPerClass,
                    'total_younger_students' => (int)$youngerStudentsPerClass * $classCount,
                ];
            }

            // Log the calculation
            Log::info('Calculated younger students per class size', [
                'total_students' => $totalStudents,
                'average_age_months' => $averageAgeMonths,
                'target_age_months' => $targetAgeMonths,
                'proportion_younger' => $proportionYounger,
                'result' => $result,
            ]);

            // Return the response
            return response()->json([
                'status' => 'success',
                'message' => 'Calculated younger students per class size',
                'data' => array_values($result),
            ], 200);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error calculating younger students', [
                'error' => $e->getMessage(),
            ]);

            // Return the error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to calculate younger students',
            ], 500);
        }
    }
}