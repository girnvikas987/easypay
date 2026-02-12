<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Facades\Invoice;

class PdfDownloadsController extends Controller
{
    public function download(Investment $record){
 
       $user = User::where('id',$record->user_id)->first();
         $invoiceNumber = 'INV2024'.$record->id.$record->user_id;
        $client = new Party([
            'name'          => 'Metvally Pay',
            'phone'         => '8295300477',
            'custom_fields' => [
                'Pincode'        => '136128',
                'Address'        => 'Shop No.41, Anaj Mandi Pehowa(Haryana)',
                'Gst' => '06AARCM6312KIZ5',
            ],
        ]);
        
        $customer = new Party([
            'name'          => $user->name.'('.$user->username.')',  
            'custom_fields' => [
                'State'          => 'Haryana',
                'Phone'          => $user->mobile,
            ],
        ]);
        
        
        $items = [
            InvoiceItem::make('Prime Membership')
                ->description('Subscription Tenure: life Time, Payment Type: Online Paid')
                ->pricePerUnit(1016.10)
                ->quantity(1)
                ->tax(182.9),
            // InvoiceItem::make('Service 2')->pricePerUnit(71.96)->quantity(2), 
            // InvoiceItem::make('Service 4')->pricePerUnit(87.51)->quantity(7)->discount(4)->units('kg'),
            // InvoiceItem::make('Service 5')->pricePerUnit(71.09)->quantity(7)->discountByPercent(9),  
            // InvoiceItem::make('Service 15')->pricePerUnit(62.21)->discountByPercent(5),
      
        ];
        
        $notes = [
            
            'Invoice Details',
            'invoice No:'.$invoiceNumber,
            'invoice Date:'.$record->created_at,
            'invoice Amount:'.$record->amount,
            
            
            
            
            '',
            '1.Tax Is Not To Be Paid Under Reserve Charge Mechanisam',
            '2. Membership Is Not Refundable',
             
        ];
        $notes = implode("<br>", $notes);
        
        $invoice = Invoice::make('Metvally Pay')
         
            ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            ->status(__('invoices::invoice.paid'))
            ->sequence(667)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date($record->created_at)
            ->dateFormat('d/m/Y') 
            ->currencySymbol('₹')
            ->currencyCode('INR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyDecimalPoint('.')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('metvallypaysmall.png'));
            // You can additionally save generated invoice to configured disk
             
        
        $link = $invoice->url();
        // Then send email to party with link
        
        // And return invoice itself to browser or have a different view
        return $invoice->stream();

    }
}
