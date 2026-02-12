<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Binary;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BinaryController extends Controller
{
    public function tree(Request $request)
    {   
        $binary = Binary::find(Auth::user()->id);
        
        if($request->has('view') && !empty($request->input('view'))){
            $binary = Binary::find($request->input('view'))->get();
        }
        return view('pages.binary.tree', [
            'user' => Auth::user(),
            'binary' => $binary,
        ]);
    }
    public function left(Request $request){
        $userId=$request->user()->id;
        $left_team = Binary::find($userId)->left_team;
        
        $query=User::whereIn('id',$left_team);
        //dd($query);

        if($request->has('search') && !empty($request->input('search'))){
            //$query->whereHas(relation : 'user', callback : function($q) use ($request){
                 $query->where('name','like','%'.$request->search.'%');
            //});
        }

        
        
        $a=$query->paginate(1)->withQueryString();
       // dd($a);
         
        
        return view('pages.binary.table', [
            'user' => $request->user(),

        ])->with('team',$a);
    }
    public function right(Request $request){
        $userId=$request->user()->id;
        $right_team = Binary::find($userId)->right_team;
        $query=User::whereIn('id',$right_team);
        //dd($query);

        if($request->has('search') && !empty($request->input('search'))){
            //$query->whereHas(relation : 'user', callback : function($q) use ($request){
                 $query->where('name','like','%'.$request->search.'%');
            //});
        }

        $a=$query->paginate(1)->withQueryString();
       // dd($a);
         
        
        return view('pages.binary.table', [
            'user' => $request->user(),

        ])->with('team',$a);
    }
}
