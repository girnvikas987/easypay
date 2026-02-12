<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Cart;
use App\Models\Product; 
use App\Models\ShippingAddress;  
use App\Models\Order;  
use App\Models\OrderItem;  
use Validator;
use App\Helper\Distribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str; 
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function getProduct(){
        $allProduct = Product::where('product_status',1)->get();
    
        $response =[
            'success'=>true,                
            'data'=>$allProduct,                
            'message'=>'Successfully fetch Product Item.'                
        ];
        
        return response()->json($response, 200);
    }
    
    
    public function addToCart(Request $request){ 

        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
            $validator = Validator::make($request->all(),[
                'product_id' => ['required', 'integer', 'max:255' , 'exists:products,id'], 
            ]);
            
            if ($validator->fails()) {
                $res = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
    
            }
            
           $userId =  $request->user()->id;
           $productId =  $request->product_id;
            $alreadyExists = Cart::where('product_id',$productId)->where('user_id',$userId)->first();
            if($alreadyExists){
                $res = [
                    'success' => false,
                    'message' => "Item already added in cart."
                ];
            }else{
                
                    Cart::create([
                        'user_id'=>$userId,
                        'product_id'=>$productId,
                        'quantity'=>1,
                        'status'=>1,
                    
                    ]);
                
                    $res = [
                        'success' => true,
                        'message' => 'successfully added in cart.'
                    ];
               
            }
            return response()->json($res, 200);
    }
    
    public function deleteCart(Request $request){ 
            $validator = Validator::make($request->all(), [
                'card_id' => ['required', 'integer', 'exists:carts,id'], 
            ]);
        
            // If validation fails, return the error response
            if ($validator->fails()) {
                $res = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
            }
        
            // Get the current user ID and the card ID from the request
            $userId = $request->user()->id; 
            $cardId = $request->card_id; 
        
            // Check if the cart item exists for the given card ID and user ID
            $cartItem = Cart::where('id', $cardId)->where('user_id', $userId)->first();
        
            // If the cart item exists, delete it
            if ($cartItem) {
                $cartItem->delete();
        
                $res = [
                    'success' => true,
                    'message' => 'Item successfully deleted from cart.'
                ];
            } else {
                // If the cart item does not exist, return a failure response
                $res = [
                    'success' => false,
                    'message' => 'Item not found in cart.'
                ];
            }
        
            // Return the response
            return response()->json($res, 200);
    }
    
    public function getCartDetails(Request $request){
        $userId =  $request->user()->id;
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();
        
        if ($cartItems->isNotEmpty()) {
            $res = [
                'success' => true,
                'data' => $cartItems->toArray(),
                'message' => 'Cart items retrieved successfully.'
            ];
        } else {
            $res = [
                'success' => false,
                'data' => [],
                'message' => 'Cart is empty!'
            ];
        }
        return response()->json($res, 200);
    }
    
    public function addQuantity(Request $request){
            $validator = Validator::make($request->all(),[
                'product_id' => ['required', 'integer', 'max:255' , 'exists:products,id'], 
                'count' => ['required', 'string', 'max:10','min:1'], 
            ]);
            
            if ($validator->fails()) {
                $res = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
    
            }
            
           $userId =  $request->user()->id;
           $productId =  $request->product_id;
           $count =  $request->count;
             $alreadyExists = Cart::where('product_id',$productId)->where('user_id',$userId)->first();
            if($alreadyExists){
                
                $alreadyExists->quantity = $count;
                $alreadyExists->save();
                $res = [
                    'success' => true,
                    'message' => "Item added in cart."
                ];
            }else{
                
                     
                
                    $res = [
                        'success' => false,
                        'message' => 'Cart Item not Found!'
                    ];
               
            }
            return response()->json($res, 200);
        
    }
    
    public function addShippingAddress(Request $request){ 
        
            $validator = Validator::make($request->all(),[
                'name' => ['required', 'string', 'max:255' ], 
                'mobile' => ['required', 'numeric', 'min:10'], 
                'address' => ['required', 'string', 'max:255'], 
                'pincode' => ['required', 'numeric', 'min:6'],   
            ]);
            
            if ($validator->fails()) {
                $res = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
    
            }
            $userId =  $request->user()->id;
            $name   =  $request->name;
            $mobile =  $request->mobile;
            $address =  $request->address;
            $pincode =  $request->pincode;
            $alreadyExists = ShippingAddress::where('user_id',$userId)->first();
            if($alreadyExists){
                $alreadyExists->name  = $name;
                $alreadyExists->phone = $mobile;
                $alreadyExists->address = $address;
                $alreadyExists->zip = $pincode; 
                $alreadyExists->save();
                
                
                    $res = [
                        'success' => true,
                        'message' => 'Address Updated Successfully.!'
                    ];
            }else{
                    ShippingAddress::create([
                        'user_id'=>$userId,
                        'name'=>$name,
                        'phone'=>$mobile,
                        'address'=>$address,
                        'zip'=>$pincode,  
                    ]); 
                
                    $res = [
                        'success' => true,
                        'message' => 'Address added Successfully.!'
                    ];
            }
            
            return response()->json($res, 200);
        
    }
    
    public function getAddress(Request $request){
        
        $userId =  $request->user()->id;
        $alreadyExists = ShippingAddress::where('user_id',$userId)->first();
        if($alreadyExists){
            
                    $res = [
                        'success' => true,
                        'data' => $alreadyExists,
                        'message' => 'Address fetch Successfully.'
                    ];
        }else{
            $res = [
                        'success' => false,
                        'data' => '',
                        'message' => 'Address not found!'
                    ];
        }
        
        return response()->json($res, 200);
    }
    
    public function placeOrder(Request $request){

        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
        
            $userId =  $request->user()->id;
            $user =  $request->user();
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();

            $totalAmount = $cartItems->sum(function($cartItem) {
                return $cartItem->product->regular_price * $cartItem->quantity; // assuming you have a quantity field in your cart
            });
             
            $wlts=Wallet::where('user_id',$userId)->first();
                    
            $detectwallet = $wlts->main_wallet;
            
            if($detectwallet >= $totalAmount && $totalAmount > 0){
                    
                 
                if ($cartItems->isNotEmpty()) {
                    
                    $addressExists = ShippingAddress::where('user_id',$userId)->first();
                    
                    if($addressExists){
                        
                   
                        
                        $order = new Order();
                        $order->user_id = auth()->user()->id;
                        $order->subtotal = $totalAmount; 
                        $order->total =$totalAmount;
                        $order->name = $addressExists->name;
                        $order->phone = $addressExists->phone; 
                        $order->address = $addressExists->address;
                        $order->zip = $addressExists->zip;
        
                        if($order->save())
                        {
                               $user->increment('order_amnt', $totalAmount);
                                foreach ($cartItems as $item) {
                                    $orderItem = new OrderItem();
                                    $orderItem->product_id = $item->product->id;
                                    $orderItem->quantity = $item->quantity; // Ensure you have a quantity field in your cart
                                    $orderItem->price = $item->product->regular_price * $item->quantity; // Calculate the subtotal
                                    $orderItem->order_id = $order->id;
                                    $orderItem->save();
                                }
                                
                                Wallet::where('user_id',Auth::user()->id)->decrement('main_wallet',$totalAmount);
                                Wallet::where('user_id',Auth::user()->id)->increment('bouns_wallet',$totalAmount);
                                
                                Distribute::EcomReferralIncome($order);
                                Cart::where('user_id', $userId)->delete();
                                $success['name'] = $addressExists->name;
                                $success['mobile'] = $addressExists->phone;
                                $success['order_date'] =  Carbon::parse($order->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                                $res = [
                                    'success' => true, 
                                    'total_amount' => $totalAmount,
                                    'data' => $success,
                                    'message' => 'Order Generated successfully.'
                                ];
                        } else {
                                $res = [
                                    'success' => false,
                                    'data' => [],
                                    'message' => 'Order not created!somthing wrong!'
                                ];
                        }
                        
                   
                        
                    }else{
                          $res = [
                                'success' => false,
                                'data' => [],
                                'message' => 'Please fill the shipping address!'
                            ];
                    }  
                } else {
                    $res = [
                        'success' => false,
                        'data' => [],
                        'message' => 'Cart is empty!'
                    ];
                }
            
            }else{
                 $res = [
                    'success' => false,
                    'data' => [],
                    'message' => 'Insufficient Amount in E wallet!'
                ];
            }
         
           return response()->json($res, 200);
             
    }
    
    
    public function orderHistory(Request $request){
       $user = $request->user()->id;

        // Get the date inputs from the request
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        
        // Use Carbon to parse the dates if provided
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay()->timezone('Asia/Kolkata') : null;
        $toDate = $toDate ? Carbon::parse($toDate)->endOfDay()->timezone('Asia/Kolkata') : null;
        
        // Query the orders table and apply filters
        $query = Order::where('user_id', $user)->with(['orderItems.product']);
        
        // Apply date filters if provided
        if ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        }
        
        if ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }
        $query->orderBy('created_at', 'DESC');
        // Paginate the results
        $perPage = 20;
        $recharges = $query->paginate($perPage);
        
        // Prepare the response
        if ($recharges->count() > 0) {
            $response = [
                'success' => true,
                'data' => $recharges->items(),
                'pagination' => [
                    'current_page' => $recharges->currentPage(),
                    'last_page' => $recharges->lastPage(),
                    'total_items' => $recharges->total(),
                ],
                'message' => 'Order history fetched successfully.',
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
                'message' => 'Order history not found!',
            ];
        }
        
        // Return the response as JSON
        return response()->json($response, 200);
        }
}
