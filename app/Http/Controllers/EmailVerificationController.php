<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailService;

class EmailVerificationController extends Controller
{

    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['status' => false,'message' => 'Already verified'], 400);
        }

      
        // Send email verification using your external email API
        // $verificationUrl = URL::temporarySignedRoute(
        //     'verification.verify',
        //     now()->addMinutes(60),
        //     ['id' => $request->user()->id, 'hash' => sha1($request->user()->email)]
        // );

        $id = $request->user()->id; // The user ID
        $hash = sha1($request->user()->email); // The hashed email to verify
        $expires = now()->addMinutes(60); // Expiration timestamp
        $signature = hash_hmac('sha256', "$id|$hash|$expires", config('app.key')); // Generate a signature

            // Construct the URL
        $verificationUrl = env('APP_URL') . "/api/verify-email/{$id}/{$hash}?expires={$expires}&signature={$signature}";

        // Use your email API to send the email
        //app('App\Services\EmailService')->sendVerificationEmail($request->user()->email, $verificationUrl); 
        $subject = 'Verify Your Email Address';
        $message = "Click the following link to verify your email: $verificationUrl";



        $username = env('EMAIL_API_USERNAME');
        $api_password = env('EMAIL_API_PASSWORD');
        $replyto = 'info@' . parse_url(env('APP_URL'), PHP_URL_HOST);
        $cright = parse_url(env('APP_URL'), PHP_URL_HOST);
        $sender = 'info@' . parse_url(env('APP_URL'), PHP_URL_HOST);
        $display = 'Verify Your Email Address';

     
        // URL encoding message to ensure spaces and special characters are handled properly
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('EMAIL_API_URL', 'https://email.adworthsms.com/pushemail.php') . '?username=' . env('EMAIL_API_USERNAME') . '&api_password=' . env('EMAIL_API_PASSWORD') . '&subject=Verify%20Your%20Email%20Address&replyto=' . urlencode($replyto) . '&cright=' . urlencode($cright) . '&sender=' . urlencode($sender) . '&display=Verify%20Your%20Email%20Address&to=' . urlencode($request->user()->email) . '&message=' . urlencode($message),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
      
      

        // Return response for logging or further processing

        // Use the EmailService to send the email
        //$response = $this->emailService->sendEmail($request->user(), $subject, $message);

        // You can log or return the response if needed
        return response()->json(['status' => true ,'message' => 'Verification email sent', 'response' => $response]);
 
    }

    public function verify(Request $request, $id, $hash)
    {

      
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully']);
    }
}
