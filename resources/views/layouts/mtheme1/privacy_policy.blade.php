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
                        <h2>Privacy Policy</h2>
                        <p>Last Updated: 01-Jan-2024</p>
                        <p>Welcome to {{env('APP_NAME','')}} . We are committed to
                            protecting your privacy and providing a secure experience while using our telecom service app. This
                            Privacy Policy outlines the types of information we may collect, how we use it, and the choices available
                            to you regarding our use of your personal information.</p>

                        <h3>1. Information We Collect</h3>
                        <p>1.1 <strong>Personal Information:</strong> We may collect personal information that you provide directly when using
                            our app, such as your name, contact details, email address, and other identifiable information.</p>
                        <p>1.2 <strong>Usage Information:</strong> We gather information about your interactions with our app, including the type of
                            device, operating system, IP address, and browsing behavior. This helps us enhance your experience and optimize
                            our services.</p>
                        <p>1.3 <strong>Location Information:</strong> With your consent, we may collect location data to provide location-based
                            services. You can manage location preferences through your device settings.</p>
<p>1.4 we are using users image data to upload in profile</p>
                        <!-- Add the rest of the Privacy Policy content here -->

                        <p>7. <strong>Updates to Privacy Policy</strong></p>
                        <p>This Privacy Policy may be updated periodically to reflect changes in our practices. We encourage you to review
                            this page regularly for any modifications.</p>

                        <p>By using our telecom service app, you agree to the terms outlined in this Privacy Policy.</p>

                        <p>Thank you for choosing {{env('APP_NAME')}} PRIVATE LIMITED.</p>

                        <!--<p>SHOP NO. 41, ANAJ MANDI PEHOWA,<br> Kurukshetra- 136128, Haryana-->
                        <!--</p>-->
                        <!--<p>Email: support@{{env('APP_NAME')}}.com</p>-->
                    </div>
                </div>
            <!--</div>-->
        </div>
    </div>
@endsection
