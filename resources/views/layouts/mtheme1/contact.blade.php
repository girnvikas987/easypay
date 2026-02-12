@extends('layouts.mtheme1')
@push('style')
    <style>

        .account__form .eye-icon{
            position: initial;
            right: 0; 
            top: 0%; 
            transform: translateY(0%); 
            cursor: pointer;
        }
        .para_team p {
    margin-bottom: 12px;
    color: white;
}
        .account-section.padding-top.padding-bottom {
            padding-top: 142px;
        }
        b.comapny_address {
    text-transform: capitalize;
    font-size: 18px;
    color: #589bff;
}

   p.comapny_address b {
  color: #589bff;

}
    </style>
@endpush
@section('content')


     
        <div class="account-section padding-top padding-bottom">
            <div class="container">
                <div class="row">
                     <div class="col-12">
                         <h2 class="heading_team">Contact Us</h2>
                         <div class="para_team">
                         <p>Welcome to {{env('APP_NAME')}} Private Limited. We're here to assist you with any inquiries or concerns you may have. Feel free to reach out to us using the following contact details:</p>

<p>Company Information:</p>
<!--<p><b class="comapny_address">phone:</b> 8295300477</p>-->

<!--<p><b class="comapny_address">Address:</b> SHOP NO. 41 ANAJ MANDI PEHOWA Pehowa Pehowa Kurukshetra, Haryana.-->
<!--</p>-->
<!--<p><b class="comapny_address">Email:</b> metvallypay@gmail.com-->


<p class="comapny_address"><b>Company URL:</b> https://{{env('APP_NAME')}}.com/
</p>

<p>How to Reach Us:</p>
<p>Should you have any questions, feedback, or require assistance, please don't hesitate to contact us via email. Our dedicated support team is committed to providing timely and helpful responses to ensure your experience with {{env('APP_NAME')}} is seamless.</p>

<p>Visit Us:</p>
<p>If you prefer face-to-face communication, you are welcome to visit our office at the mentioned address during our business hours.</p>

<p>Business Hours:</p>
<p>Monday to Friday: 9:00 AM - 6:00 PM
Saturday: 9:00 AM - 1:00 PM
Sunday: Closed</p>
<!--<p>Connect With Us Online:</p>
<p>
Stay updated with Metvally Pay by following us on social media:

Facebook
Twitter
Instagram
</p>-->
<p>Thank you for choosing {{env('APP_NAME')}} Private Limited. We look forward to serving you!

</p>

</div>













                     </div>
                </div>
            </div>
        </div>
       
    
    
        
@endsection