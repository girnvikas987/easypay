
<style>
    :root {
    --bg:#589bff;
}
.header-top{
    display:none;
}
.header-trigger span{
    background:var(--bg);
}
.footer__widget .footer-links li a:hover {
    color:var(--bg);
}
.menu li a:hover {
    color:var(--bg);
}
.header-trigger.active span::after {
   
    background:var(--bg);
}
.header-trigger.active span::before{
    background:var(--bg);
}
a.search--btn.me-4.text--base{
    display:none;
}
.header-trigger span::after, .header-trigger span::before{
     background:var(--bg);
}
.banner__wrapper.d-flex.align-items-center.justify-content-between{
    margin-top:80px;
}
.cmn--btn::after{
    background:var(--bg) !important;
}
.cmn--btn::before{
     background:var(--bg) !important;
}
.three_option{
    border: 1px solid var(--bg);
}

a.readmore_detail, .scrollToTop{
    background:var(--bg) !important;
}
.menu li {
  
    border:none !important;
}


.footer__widget .widget-title::before{
    background:var(--bg) !important;
}
.footer__widget .footer-links li::before{
     background:var(--bg) !important;
}

.footer__widget .footer-links li{
    border:none !important;
}
</style>



<div class="header">
    <div class="header-top">
        <div class="container">
            <div class="header__top__wrapper d-flex flex-wrap align-items-center justify-content-center justify-content-md-between text-center">
                 
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-area">
                <div class="logo"><a href="/"><img src="{{asset('mtheme1/assets/images/S2_pay.png')}}" class="" alt="{{env('APP_NAME')}}" style="width:;height:"></a></div>
                 <ul class="menu">
                    <li>
                        <a href="{{route('home')}}">Home</a>
                    </li>
                    <li>
                        <a href="https://play.google.com/store/apps/details?id=com.app.s2pay">About</a>
                    </li>
                    
                    <li>
                        <a href="#vision">Vision</a>
                    </li>
                    <li>
                        <a href="#faq">FAQ</a>
                    </li>
                    
                    <!--<li>
                        <a href="roc">ROC</a>
                    </li>-->
                 
                    <li>
                       <a href="{{route('contact')}}">Contact Us</a>
                    </li>
                    @auth
                            
                             
                    @else
                        <li>
                            <!--<a href="{{ route('register')}}">Register</a>-->
                        </li>
                    @endif
                  
                </ul>    
                        @auth
                            
                            <div class="button__wrapper d-none d-lg-block">
                                 <a href="{{ route('dashboard')}}" class="cmn--btn">Dashboard</a>
                                 
                            </div>
                        @else
                             
                              
                             <div class="  d-none d-lg-block">
                                 <a href="" class="cmn--btn">Coming Soon</a>
                                 
                            </div>
                        @endif
                  
                

                 <div class="header-trigger-wrapper d-flex d-lg-none align-items-center">
                    <div class="mobile-nav-right d-flex align-items-center"></div>
                     <a href="#0" class="search--btn me-4 text--base"><i class="fas fa-search"></i></a> 
                    <div class="header-trigger d-block d--none">
                        <span></span>
                    </div>
                </div>   
            </div>
        </div>
    </div>
</div>