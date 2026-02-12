@extends('layouts.mtheme1')
@push('style')
    <style>
        .form_inner_content {
            width: 100%;
            margin: 20px auto;
            text-align: center;
            position: relative;
            z-index: 0;
            box-shadow: 0 0 35px rgb(0 0 0 / 10%);
            padding: 50px 40px;
            background: linear-gradient(#04083f 0%, #020424 100%);
            border-radius: 15px;
        }
        
        
        .form_inner_content h3 {
            margin: 0;
            padding-bottom: 15px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: .8px;
        }
        
        input.form-control {
            height: 42px;
            border-radius: 0px;
        }
        
        
        .form-group i {
            position: absolute;
            top: 43%;
            right: 9px;
        }
        .checkbox.form-group {
            display: flex;
            justify-content: space-between;
        }
        .remove_data a {
            color: #fff;
            font-size: 15px;
        }
        
        .remove_data {
            text-align: end;
        }
        .form_check_data {
            display: flex;
            align-items: center;
        }
        
        input.form_check_input {
            width: 20px;
            height: 20px;
            vertical-align: top;
            border: 2px solid #c5c3c3;
            border-radius: 0;
            margin-right: 7px;
        }
        
        label.form_check_label {
            margin-bottom: 0;
        }
        
        button.submit_login {
            position: relative;
            display: inline-block;
            width: 100%;
            color: #000;
            overflow: hidden;
            text-transform: capitalize;
            display: inline-block;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 17px;
            font-weight: 400;
            border-radius: 4px;
            border: none;
            padding: 10px;
        
            background: linear-gradient(90deg, #de9f17 0%, #d19c15 33%, #fff58a 67%, #ffd147 100%);
        }
        button.submit_login:focus{
            outline:none;
        }
        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: none;
            outline: 0;
            box-shadow: none;
        }
        
        .form-group{
            margin-bottom:10px !important;
        }
        .error-massage-id{
            text-align: initial;
        
        }
        
        select.select_data {
            width: 100% !important;
            font-size: 14px !important;
            font-weight: 400 !important;
            border-radius: 0px !important; 
            border: 1px solid #d3d0d0 !important;
            padding: 5px 10px  !important;
        height:42px !important;
        
        }
        .error-massage-id p {
            font-size: 13px;
            text-align: initial;
            color: red;
        }
        
        .error-massage-id{
            margin-bottom:10px;
        }
    </style>
@endpush
@section('content')


     
<div class="account-section padding-top padding-bottom">
    <div class="container mt-5">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-xl-5 d-none d-lg-block">
                <div class="section__thumb rtl me-5">
                    <img src="{{asset('mtheme1/assets/images/account/thumb.png')}}" alt="account">
                </div>
            </div>
            <div class="col-lg-6 col-xl-5">
                <div class="account__form__wrapper">
                    <h3 class="title">Register</h3>
                    <span id="ref"></span>
                    <span id="ref1"></span>
                    <!--<a class="" onclick="return register();">chk</a>-->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                
                        <!-- Sponsor -->
                        <div class="form-group">
                            <label for="sponsor">Sponsor</label>
                            <input id="sponsor" class="form-control" type="text" name="sponsor" value="@if($ref){{$ref}}@else{{old('sponsor')}}@endif" required autofocus autocomplete="sponsor"  onchange="return validateUser()">
                            @error('sponsor')
                                <span class="text-danger">{{$message}} </span>
                            @enderror
                            <span class="" id="message"></span>
                        </div>

                         <!-- Name -->
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" class="form-control" type="text" name="name" value="{{old('name')}}" required >
                            @error('name')
                                <span class="text-danger">{{$message}} </span>
                            @enderror
                        </div>
                        
                         <!-- Username -->
                        <!--<div class="form-group">-->
                        <!--    <label for="username">Username</label>-->
                        <!--    <input id="username" class="form-control" type="text" name="username" value="{{old('username')}}" required >-->
                        <!--    @error('username')-->
                        <!--        <span class="text-danger">{{$message}} </span>-->
                        <!--    @enderror-->
                        <!--</div>-->

                         <!-- Mobile -->
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input id="mobile" class="form-control" type="text" name="mobile" value="{{old('mobile')}}" required >
                            @error('mobile')
                                <span class="text-danger">{{$message}} </span>
                            @enderror
                        </div>
                        
                         <!-- Email Address -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" class="form-control" type="text" name="email" value="{{old('email')}}" required >
                            @error('email')
                                <span class="text-danger">{{$message}} </span>
                            @enderror
                        </div>
                        
                         <!-- TRC20 Address -->
                        <div class="form-group">
                            <label for="trc20_address">TRC20 Address</label>
                            <input id="trc20_address" class="form-control" type="text" name="trc20_address" value="{{old('trc20_address')}}" required >
                            @error('trc20_address')
                                <span class="text-danger">{{$message}} </span>
                            @enderror
                        </div>
                        
                         
                         <!-- Password -->
                        <div class="form-group">
                            <label for="password">password</label>
                            <input id="password" class="form-control" type="password" name="password" value="{{old('password')}}" required >
                            @error('password')
                                <span class="text-danger">{{$message}} </span>
                            @enderror
                        </div>
                        
                         
                       
                         
                         <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" class="form-control" type="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}" required >
                            @error('password_confirmation')
                                <span class="text-danger">{{$message}} </span>
                            @enderror
                        </div>
                         
                        <button class="btn cmn--btn mt-4" type="submit" name="register">Register</button>
                        
                    </form>
                
                    <p class="mt-4">Already you have an account in here? <a class="ms-2 text--base" href="{{route('login')}}">Login Account</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
       
    
    @push('scripts')
        <script>
            function validateUser() {
                var message = document.getElementById("message");
                var username = $('#sponsor').val();
                if (username == ''){
                    alert("Please enter your Sponsor");
                    return false;
                }else{
                    $.ajax({
                        url : "{{route('validate.user')}}",
                        method : 'POST',
                        data:{username:username, _token:'{{csrf_token()}}'},
                        success:function(response){
                            var res = response;
                            if (res.res=="success") {
                                message.innerHTML = res.name;
                                message.style.color = "green";
                            } else {
                                message.style.color = "red";
                                message.innerHTML = res.message;
                            }                          
                            return;
                             
    
                        }
                    });
                }
                                     
            }
    
            function validatePackageType() {
                var pkgsId = $('#package option:selected').attr('data-type');
                if(pkgsId=="fix"){
                    $('#amountSection').hide();
                }else{
                    $('#amountSection').show();
                }
            }
        </script>
            
    @endpush
        
@endsection