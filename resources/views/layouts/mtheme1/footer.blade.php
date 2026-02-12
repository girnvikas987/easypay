   <link
         rel="stylesheet"
         href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
         />
     <link
         rel="stylesheet"
         href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
         />
<footer class="footer-section section-bg">
    <div class="footer-top padding-top pb-5 border-bottom border--white">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-sm-6">
                    <div class="footer__widget">
                        <h3 class="widget-title"><img src="{{asset('mtheme1/assets/images/S2_pay.png')}}" class="" alt="{{env('APP_NAME')}}" style="width:100px;height:"></h3>
                        <p>Your satisfaction is our success. We are dedicated to providing top-notch customer service, addressing your queries and concerns promptly.</p>
                       <div class="social_links">
                           <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                       </div>
                    </div>
                   
                </div>
                <div class="col-xl-3 col-lg-3 col-sm-6">
                    <div class="footer__widget">
                        <h3 class="widget-title">Useful Links</h3>
                        <ul class="footer-links mb-2">
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#vision">Vision </a></li>
                           
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-sm-6">
                    <div class="footer__widget">
                        <h3 class="widget-title ">Company</h3>
                        <ul class="footer-links mb-2">
                        
                        <!--<li><a href="contact">Contact Us</a></li>-->
                        <li><a href="{{route('conditions')}}">Terms and Conditions </a></li> 
                               <li><a href="{{route('privacy')}}">Privacy and Policy</a></li>
                               <!--<li><a href="{{route('return')}}">Return Policy</a></li>-->
                               <!--<li><a href="{{route('refund')}}">Refund Policy</a></li>-->
                               <!--<li><a href="register">Register</a></li>-->
                            
                    
                        </ul>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p class="copyright text-center py-3">
                Copyright &copy; 2024 {{env('APP_NAME')}} All Rights Reserved.
            </p>
        </div>
    </div>
</footer> 