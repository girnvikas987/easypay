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
    </style>
@endpush
@section('content')


     
        <div class="account-section padding-top padding-bottom">
            <div class="container">
                <div class="row">
                     <div class="col-12">
                         <h2 class="heading_team">Terms and Conditions</h2>
                         <div class="para_team">
                         <p>{{env('APP_NAME')}} PAY PRIVATE LIMITED the Company is a provider of telecom services, specializing in recharge and DTH bill payments..</p>

<p>Acceptance of Terms:</p>
<p>By using the services provided by {{env('APP_NAME')}} Pvt Ltd, you agree to comply with and be bound by these terms and conditions. If you do not agree with any part of these terms, you may not use our services.</p>
<p>Service Description:</p>
<p>{{env('APP_NAME')}} Pvt Ltd offers services related to telecom, including mobile recharge and DTH bill payment. The company reserves the right to modify, suspend, or terminate any aspect of its services without prior notice.</p>
<p>User Responsibilities:</p>
<p>Users are responsible for providing accurate and up-to-date information when using {{env('APP_NAME')}} services. The company is not liable for any losses or damages resulting from inaccurate information provided by the user.</p>
<p>Payment and Refund Policies:</p>
<p>All payments made through {{env('APP_NAME')}} are subject to our payment and refund policies, which can be found on our website. Refunds may be issued based on the specific circumstances outlined in our refund policy.</p>
<p>Security and Privacy:</p>
<p>{{env('APP_NAME')}} prioritizes the security and privacy of user information. However, users are responsible for safeguarding their account credentials. The company is not liable for any unauthorized access to user accounts.</p>

<p>Intellectual Property:</p>
<p>All content and materials provided by {{env('APP_NAME')}}, including but not limited to logos, trademarks, and software, are the property of the company and may not be used without prior written consent.</p>
<p>Limitation of Liability:</p>
<p>{{env('APP_NAME')}} Pvt Ltd shall not be liable for any direct, indirect, incidental, special, or consequential damages resulting from the use or inability to use our services.</p>

<p> Changes to Terms</p>
<p>{{env('APP_NAME','')}} reserves the right to modify
these Terms at any time. You are responsible for reviewing these Terms
periodically for any updates or changes.</p>
<p> Contact Information</p>
<p>For questions, concerns, or inquiries related
to these Terms or {{env('APP_NAME','')}}'s investment services, please contact us using
the provided contact information.</p>

</div>













                     </div>
                </div>
            </div>
        </div>
       
    
    
        
@endsection