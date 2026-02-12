<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Support\Facades\File;
// use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    use PDF;


class PDFController extends Controller
{
  

        public function generatePDF(Request $request)
        {
            
            $userId = $request->user()->id;
            $investment = Investment::where('user_id', $userId)->first();
        
            if ($investment) {
                
                $invoiceNumber = 'INV2024'.$investment->id+$investment->user_id;
                $data = [
                    'title' => 'Welcome to MetvallyPay.com',
                    'date' => date('m/d/Y'),
                    'investments' => $investment,
                    'invoice_number' => $invoiceNumber
                ];
        
                $pdf = PDF::loadView('myPDF_new', $data);
        
                // Generate a unique filename for the PDF
                $filename = 'metvalleyPay_' . time() . '.pdf';
        
                // Save the PDF to the public directory
              $pdf->save(public_path('temp_pdfs/' . $filename));


        
                $pdfUrl = asset('public/temp_pdfs/' . $filename);
              
                $response = [
                    'status' => true,
                    'message' => 'PDF generated successfully.',
                    'pdf_url' => $pdfUrl // Provide the URL to access the PDF
                ];
               //File::delete(public_path('temp_pdfs/' . $filename));

                return response()->json($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Please Buy Subscription Package.',
                    'pdf_url' => null
                ];
        
                return response()->json($response, 200);
            }
        }

}
