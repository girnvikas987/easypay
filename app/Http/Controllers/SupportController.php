<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Support;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index(Request $request)
    {   

        $tickest=Support::where('user_id',Auth::user()->id)->get();
        return view('pages.support', [
            'user' => $request->user(),
            'tickets' => $tickest
        ]);
        
        
    }
    public function store(Request $request): RedirectResponse
    {   
        //        dd($request->all());
        
        $request->validate([
            'subject' => ['required', 'string', 'max:255' ],
            'message' => ['required', 'string', 'min:1' ],            
        ]);

        $invest = Support::create([

            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status'  => 0
        ]);

        $request->session()->flash('status', 'Ticket generated!');
        //Auth::login($user);

        return redirect('./support');
    }
    
    //////////////////////////////////////////////////App Api start ///////////////////////////////////////////////////////////////////////
    public function requestSupport(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'subject' => ['required', 'string', 'max:255' ],
            'message' => ['required', 'string', 'min:1' ],   
        ]);
        
        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        DB::beginTransaction();
        try {
            $invest = Support::create([
    
                'user_id' => Auth::user()->id,
                'subject' => $request->subject,
                'message' => $request->message,
                'status'  => 0
            ]);
        
            DB::commit();
                
            $response = [
                'success' => true,
                'message' => "Support Request sent successfully."
            ]; 
        } catch (\Exception $e) {
             
            DB::rollBack();
            
             $response = [
                'success' => false,
                'data' => $e,
                'message' => "Something Wrong."
            ]; 
        }
        return response()->json($response, 200);
  
    }
    
    
    public function supportHistory(Request $request){
         $userId = $request->user()->id;
    
        // Retrieve a single record based on the condition
        $investment = Support::where('user_id', $userId)->get();
    
        // Check if the record is found
        if ($investment) {
            // Paginate the results manually
            $perPage = 10;
            $currentPage = 1; // You may need to adjust this based on the requested page
            $items = collect($investment); // Convert the single record to a collection
        
            $paginatedItems = new LengthAwarePaginator(
                $items->forPage($currentPage, $perPage),
                $items->count(),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        
            $response = [
                'success' => true,
                'data' => $paginatedItems->items(),
                'pagination' => [
                    'current_page' => $paginatedItems->currentPage(),
                    'last_page' => $paginatedItems->lastPage(),
                    'total_items' => $paginatedItems->total(),
                ],
                'message' => 'Support History Fetch Successfully.',
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
                'message' => 'Support history not fetch!',
            ];
        }

        return response()->json($response, 200);
    }
    //////////////////////////////////////////////////App Api end ///////////////////////////////////////////////////////////////////////
}
