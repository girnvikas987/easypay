<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index(String $incometype,Request $request)
    {   
        $user_id=Auth::user()->id;
        $query=Transaction::where('user_id',$user_id)->where('income',$incometype);
        
        if($request->has('search') && !empty($request->input('search'))){
            $query->whereHas(relation : 'user', callback : function($q) use ($request){
                 $q->where('name','like','%'.$request->search.'%');
            });
        }

        
        
        $transaction=$query->paginate(10)->withQueryString();

        return view('pages.transactions', [
            'user' => Auth::user(),
        ])->with('transactions',$transaction);
    }
        
     
}
