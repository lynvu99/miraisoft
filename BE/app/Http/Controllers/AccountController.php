<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class AccountController extends Controller
{
    /**
     * Display a listing of the accounts with pagination.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10); // Default to 10 items per page
            $accounts = Account::paginate($perPage);

            Log::info('Accounts retrieved successfully', ['count' => $accounts->total()]);

            return response()->json([
                'status' => 'success',
                'data' => $accounts->items(),
                'pagination' => [
                    'total' => $accounts->total(),
                    'per_page' => $accounts->perPage(),
                    'current_page' => $accounts->currentPage(),
                    'last_page' => $accounts->lastPage(),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving accounts', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve accounts',
            ], 500);
        }
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        try {
            $account = Account::create($request->validated());

            Log::info('Account created successfully', ['id' => $account->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Account created successfully',
                'data' => $account,
            ], 201);
        } catch (Exception $e) {
            Log::error('Error creating account', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create account',
            ], 500);
        }
    }

    /**
     * Display the specified account.
     */
    public function show($id)
    {
        try {
            $account = Account::findOrFail($id);

            Log::info('Account retrieved successfully', ['id' => $account->id]);

            return response()->json([
                'status' => 'success',
                'data' => $account,
            ], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving account', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found',
            ], 404);
        }
    }

    /**
     * Update the specified account in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        try {
            $account->update($request->validated());

            Log::info('Account updated successfully', ['id' => $account->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Account updated successfully',
                'data' => $account,
            ], 200);
        } catch (Exception $e) {
            Log::error('Error updating account', ['id' => $account->id, 'error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update account',
            ], 500);
        }
    }
    /**
     * Remove the specified account from storage.
     */
    public function destroy($id)
    {
        try {
            $account = Account::findOrFail($id);
            $account->delete();

            Log::info('Account deleted successfully', ['id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Account deleted successfully',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error deleting account', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete account',
            ], 500);
        }
    }
}