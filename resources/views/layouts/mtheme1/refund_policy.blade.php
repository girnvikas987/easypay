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
                        <h2>Refund Policy</h2>
                       <p>To cancel the policy and issue a refund to your client within 10 days, you'll need to follow your company's refund policy. You can initiate the cancellation process by contacting your client directly and informing them of the cancellation and refund procedure. Additionally, you may need to process the refund through your payment system or accounting department, depending on your company's procedures. It's important to communicate clearly with your client and ensure that the refund is processed promptly and accurately. It's great to hear that your refund process was completed within 3 working days! If you have any other questions or need further assistance, feel free to ask.</p>
                    </div>
                </div>
            <!--</div>-->
        </div>
    </div>
@endsection
