<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class TransactionController extends Controller
{
    public function all(Request $request)
    {   
        $query=$request->user()->transactions();
        $a=$query->paginate(10)->withQueryString();
        return view('pages.transactions', [
            'user' => $request->user(),
        ])->with('transactions',$a);
    }
    public function all_transactions(Request $request)
    {   
    
        $userId = $request->user()->id;

        // Retrieve all transactions for the user
        // Start building the query
        $query = $request->user()->transactions()->orderBy('created_at', 'desc');
 
        // Apply filter for 'tx_type' if provided in the request
        if($request->has('tx_type') != ''){
        
        
        if ($request->has('tx_type')) {
            $query->where('tx_type', $request->input('tx_type'));
        }
    }
        if($request->has('wallet_type') != ''){
        
        
        if ($request->has('wallet_type')) {
            $query->where('wallet', $request->input('wallet_type'));
        }
    }
        // Apply filter for 'income' if provided in the request
        if ($request->has('income')) {
            $query->where('income', $request->input('income'));
        }
        $ttlAmount = $query->sum('amount');
        // Paginate the filtered results
        $transactions = $query->paginate(10);
      
        // Check if any transactions are found
        if ($transactions->count() > 0) {
            
            
             $transactionsData = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'tx_user' => $transaction->tx_user,
                'amount' => $transaction->amount,
                'charges' => $transaction->charges,
                'tx_type' => $transaction->tx_type,
                'type' => $transaction->type,
                'wallet' => $transaction->wallet,
                'income' => $transaction->income,
                'tx_id' => $transaction->tx_id,
                'level' => $transaction->level,
                'close_amount' => $transaction->close_amount,
                'remark' => $transaction->remark,
                'status' => $transaction->status,
                'created_at' => Carbon::parse($transaction->created_at)->timezone('Asia/Kolkata')->toDateTimeString(),
                'updated_at' => Carbon::parse($transaction->updated_at)->timezone('Asia/Kolkata')->toDateTimeString(),
            ];
        });
            $response = [
                'success' => true,
                'data' => $transactionsData,
                'ttlAmount' => $ttlAmount,
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'total_items' => $transactions->total(),
                ],
                'message' => 'Transaction History Fetch Successfully.',
            ];
        } else {
            $response = [
                'success' => true,
                'data' => [],
                'ttlAmount' => 0,
                'pagination' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'total_items' => 0,
                ],
                'message' => 'Transaction history not fetched!',
            ];
            
        }
        
        return response()->json($response, 200);
        
    }
    
    public function incomeHistory(Request $request){
        $type = $request->type;
        $page = $request->page;
        $user = $request->user();

        // Filter and paginate the transactions directly
        $perPage = 20;
        $currentPage = $page ?: 1; // You may need to adjust this based on the requested page
        
        
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
    
        // Use Carbon to parse the dates if provided
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay() : null;
        $toDate = $toDate ? Carbon::parse($toDate)->endOfDay() : null;
        $query = $user->transactions()->where('income', $type);
        
            if ($fromDate) {
                $query->where('created_at', '>=', $fromDate);
            }
        
            if ($toDate) {
                $query->where('created_at', '<=', $toDate);
            }


        // $filteredTransactions = $user->transactions()->where('income', $type)->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $currentPage);
            $filteredTransactions = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $currentPage);
            
        if ($filteredTransactions->count() > 0) {
            $response = [
                'success' => true,
                'data' => $filteredTransactions->items(),
                'pagination' => [
                    'current_page' => $filteredTransactions->currentPage(),
                    'last_page' => $filteredTransactions->lastPage(),
                    'total_items' => $filteredTransactions->total(),
                ],
                'message' => 'Income History Fetch Successfully.',
            ];
        } else {
            $response = [
                'success' => true,
                'data' => [],
                 'pagination' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'total_items' => 0,
                ],
                'message' => 'Income history not fetch!',
            ];
        }

        return response()->json($response, 200);
        
    }
    public function todayIncomeHistory(Request $request)
    {
        $user = $request->user();
        
        // Fetch today's self and level income
        $today = now()->toDateString();
        $selfAndLevelIncome = $user->transactions()
            ->whereDate('created_at', $today)
            ->whereIn('income', ['self', 'level'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    
        if ($selfAndLevelIncome->count() > 0) {
            $response = [
                'success' => true,
                'data' => $selfAndLevelIncome->items(),
                'pagination' => [
                    'current_page' => $selfAndLevelIncome->currentPage(),
                    'last_page' => $selfAndLevelIncome->lastPage(),
                    'total_items' => $selfAndLevelIncome->total(),
                ],
                'message' => 'Self and Level Income for Today Fetched Successfully.',
            ];
        } else {
            $response = [
                'success' => true,
                'data' => [],
                'pagination' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'total_items' => 0,
                ],
                'message' => 'No Self and Level Income for Today.',
            ];
        }
    
        return response()->json($response, 200);
    }

}
