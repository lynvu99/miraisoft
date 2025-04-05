<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\HtmlFileController;
use App\Http\Controllers\StudentAgeController;
use App\Http\Controllers\FurthestPeopleController;
use App\Http\Controllers\AgeDifferenceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('accounts', AccountController::class);
Route::get('html-file', [HtmlFileController::class, 'getHtmlFile']);
Route::get('younger-students', [StudentAgeController::class, 'calculateYoungerStudents']);
Route::get('largest-age-difference', [AgeDifferenceController::class, 'calculateLargestAgeDifference']);
Route::get('furthest-people', [FurthestPeopleController::class, 'calculateFurthestPeople']);