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
        .account-section.padding-top.padding-bottom {
            padding-top: 182px;
        }
    </style>
@endpush
@section('content')


     
        <div class="account-section padding-top padding-bottom">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6 col-xl-5 d-none d-lg-block">
                        <div class="section__thumb rtl me-5">
                            <img src="{{asset('mtheme1/assets/images/account/thumb.png')}}" alt="account">
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-5">
                        <div class="account__form__wrapper">
                            <h3 class="title">Login</h3>
                             @if (Session::get('error'))
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                            <form class="form account__form" action="{{ route('login') }}" method="post">
                                @csrf
                            
                               <div class="form-group">
                                  <input type="text" class="form-control "  placeholder="Username" id="username" name="username" value="{{old('username')}}" class="">
                                @error('username')
                                    <span class="text-danger"> {{$message}} </span>
                                @enderror
                               </div>
                            <div class="form-group">
                            <input type="password" class="form-control " placeholder="Password" id="password" name="password" value="{{old('password')}}" class="form-control with-border bg-white">
                            @error('password')
                                    <span class="text-danger"> {{$message}} </span>
                                @enderror
                                
                                    
                              </div>
                              <div class=" d-flex flex-wrap align-items-center">
                                   <div class="form--check me-4">
                                      <input type="checkbox" id="rem-me">
                                       <label for="rem-me">Remember Me</label>
                                    </div>
                                   <a href="{{ route('password.request') }}" class="forgot-pass text--base">Forgot Password?</a>
                             </div>
                             <button class="btn cmn--btn mt-4" type="submit" name="login">Login</button>
                           </form>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    
    
        
@endsection