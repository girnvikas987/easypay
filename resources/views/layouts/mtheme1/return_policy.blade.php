@extends('layouts.mtheme1')

@push('style')
    <style>
        .account__form .eye-icon {
            position: initial;
            right: 0;
            top: 0%;
            transform: translateY(0%);
            cursor: pointer;
        }

        .account-section.padding-top.padding-bottom {
            padding-top: 142px;
        }
    </style>
@endpush

@section('content')
    <div class="account-section padding-top padding-bottom">
        <div class="container">
            <!--<div class="row justify-content-between align-items-center">-->
               
                <div class="col-lg-12">
                    <div class="account__form__wrapper">
                        <h2>Return Policy</h2>
                        <p>LAt {{env('APP_NAME','')}}, we strive to ensure your complete satisfaction with your purchases. If for any reason you are not satisfied with your purchase, we will gladly accept returns within [7days] of delivery. Please review the following guidelines for our return process:</p>
                      

                        <h3>1.Eligibility for Returns:</h3>
                        <p>To be eligible for a return, items must be unused and in the same condition that you received them. They must also be in the original packaging. Certain items such as [list any non-returnable items] are not eligible for returns.</p>
                       
                      <h3>2.Initiation of Returns:</h3>
                      <p>To initiate a return, please contact our customer service team at [customer service email/phone number] within [X days] of receiving your order. Please provide your order number and details of the item(s) you wish to return.</p>
                   
                       <h3>3. Return Shipping:</h3>
                      <p>Customers are responsible for the return shipping costs unless the return is due to an error on our part (e.g., wrong item shipped, defective product). We recommend using a trackable shipping service for returns.</p>
                   
                   <h3>
                       4. Refund Process:
                   </h3>
                   
                   <p>Once your return is received and inspected, we will send you an email to notify you that we have received your returned item(s). Refunds will be issued to the original payment method used for the purchase. Please allow [5 days] for the refund to be processed and reflected in your account.</p>
                   <h3>5. Exchanges:</h3>
                   <p>If you wish to exchange an item for a different size, color, or style, please contact our customer service team to arrange the exchange. Exchanges are subject to availability.</p>
                   <h3>6. Restocking Fee:</h3>
                   <p>A restocking fee of [0%] may apply to certain returns. This fee will be deducted from your refund.</p>
                  <h3>7. Damaged or Defective Items:</h3> 
                   <p>If you receive a damaged or defective item, please contact us immediately. We will arrange for a replacement or refund as soon as possible.</p>
                   <h3>8. Final Sale Items:</h3>
                   <p>Items marked as "final sale" are not eligible for returns or exchanges.</p>
                   <h3>9. Policy Updates:</h3>
                   <p>We reserve the right to update our return policy at any time. Any changes will be effective immediately upon posting on our website. By making a purchase on {{env('APP_NAME','')}}, you agree to adhere to the terms of our return policy.</p>
                   <p>If you have any questions or concerns regarding our return policy, please don't hesitate to contact us.</p>
                   
                   
                    </div>
                </div>
            <!--</div>-->
        </div>
    </div>
@endsection
