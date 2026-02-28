@extends('layouts.mtheme1')
@push('style')
<link
   rel="stylesheet"
   href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
   />
<style>
   .social_links a {
   width: 40px;
   height: 40px;
   background: #589bff;
   text-align: center;
   line-height: 40px;
   border-radius: 50%;
   color: #fff;
   font-size: 26px;
   }
   .social_links {
   margin: 10px 0px;
   }
   .banner__content .title {
   font-size: 42px;
   color:var(--text_color);
   }
   .banner__content p{
        color:var(--light_color);
   }
   .container.data {
   position: absolute;
   z-index: 99999;
   top: 102px;
   }
   .paymet_data span i {
   font-size: 18px;
   color:var(--light_color);
   font-weight: 800;
   }
   @media screen and (max-width: 576px) {
   .container.data {
   position: absolute;
   z-index: 99999;
   top: 150px;
   }
   }
   img.download_imge {
   width: 100%;
   border-radius: 10px;
   }
   img.image_mobile {
   width: 100%;
   margin-bottom:20px;
   border-radius: 10px;
   }
   .meta_detail_detail h4{
   font-size:32px;
   }
   .bg-video-wrap-detail{
   height:400px;
   }
   .choose__us__content h6 {
   color: #fff;
   margin:20px 0px;
   font-weight: 600;
   font-size: 24px;
   }
   .paymet_data span {
   margin: 0px 8px;
   display: block;
   font-size: 20px;
 color:var(--light_color);
   }
   
   .section__header.mb-0 ul li b {
        color:var(--text_color);
}
   .section__header.mb-0 ul li {
        color:var(--light_color);
}
   .paymet_data {
   margin: 20px 0px;
   }
   .three_option_solution img {
    width: 150px;
    margin: 10px auto;
    display: block;
}

.three_option_solution {
    text-align: center;
}
   .mobile_services {
   display: flex;
   align-items: center;
   justify-content: space-between;
   border-radius: 6px;
   border: 4px solid #fff;
   padding: 14px;
   flex-wrap: nowrap;
   }
   .mobile_services p {
   font-size: 20px;
   color: #fff;
   }
   .service_market {
    background: #fff3e5;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 10px;
}
   @media screen and (max-width: 768px) {
   .mobile_services {
   align-items: baseline !important;
   flex-direction: column !important;
   }
   }
   .mobile_services p span {
   width: 100%;
   }
   .three_option {
   display: block;
   text-align: center;
   }
   .three_option_solution {
   border: 1px solid var(--bg);
   padding: 16px;
   background: #ffffff;
   margin-bottom:10px;
   border-radius: 10px;
   }
   .three_option_solution h4 {
   text-align: center;
   margin-bottom: 14px;
   }
   .three_option_solution p {
   font-size: 16px;
     color:var(--text_color);
   }
   .three_option_solution p b {
   color: #fff;
   }
   .service_market h4 {
   font-size: 18px;
     color:var(--text_color);
   margin-bottom:10px;
   }
   .service_market span {
   font-size: 10px;
   }
   .service_market p {
   font-size: 14px;
   }
   .service_market h6 {
   font-size: 10px;
   word-break: break-all;
   }
   .section__header p {
   color: var(--light_color);
   margin-bottom: 15px;
   font-size: 18px;
   line-height: 1.6;
   }
   .competitive_data table {
   width: 100%;
   }
   .competitive_data th, td {
   color: #fff;
   font-size: 15px;
   padding: 5px;
   border: 1px solid #ffffff47;
   font-weight: 400;
   }
   .competitive_data {
   overflow: auto;
   }
   td.secuit_d {
   font-weight: 700;
   }
   .team_withdrewal {
   width: 100px;
   width: 100px;
   height: 100px;
   border-radius: 50%;
   margin-bottom: 5px;
   overflow: hidden;
   }
   .team_withdrewal img{
   width:100%;
   }
   .team_data {
   padding: 10px;
   border: 1px solid #589bff;
   border-radius: 10px;
   margin-bottom: 15px;
   }
   .team_members h5 {
   font-size: 14px;
   margin-bottom: 3px;
   }
   
   
   .section__header-title{
       color:var(--text_color);
   }
   
   .three_option_solution h4{
         color:var(--text_color);
   }
   @media screen and (max-width: 768px) {
 .banner__content .title {
    font-size: 30px;
}

.banner__wrapper.d-flex.align-items-center.justify-content-between {
    margin-top: 112px !important;
}


}
</style>
@endpush
@section('content')
<section class="banner-section overflow-hidden">
   <!--<video src="{{asset('mtheme1/assets/images/stock_data.mp4')}}" class="vedio_text" loop muted autoplay>-->
   <!--  </video>-->
   <!--  <video src="{{asset('mtheme1/assets/images/Sequence_01.mp4')}}" class="vedio_text1" loop muted autoplay>-->
   <!--  </video>-->
   <div class="container">
      <div class="banner__wrapper d-flex align-items-center justify-content-between">
         <div class="banner__content">
            <!--<p><b>Welcome To </b></p> -->
            <h1 class="title">{{env('APP_NAME')}} Seamless Recharge, Empowered Connectivity</h1>
            <p>{{env('APP_NAME')}} offers a seamless, secure, and reliable platform for recharges and payments with multiple options and no hidden fees. Join us to experience effortless connectivity and be part of the future of recharge services.</p>
            <a href="{{asset('mtheme1/assets/images/easydigipay.apk')}}" class="cmn--btn" download>Download Android APP</a>
         </div>
         <div class="banner__thumb d-none d-lg-block">
            <img src="{{asset('mtheme1/assets/images/54kqVc1cG.png')}}" alt="banner">
         </div>
      </div>
   </div>
</section>

<!--<section class="choose-us padding-top" id="">-->
<!--   <div class="container">-->
<!--      <div class="row">-->
<!--         <div class="col-lg-12">-->
<!--            <div class="section__header text-center max-p">-->
<!--               <h2 class="section__header-title">Empowering Your Financial Decision-Making</h2>-->
<!--            </div>-->
<!--         </div>-->
<!--      </div>-->
<!--      <div class="row">-->
<!--         <div class="col-12">-->
<!--            <div class="mobile_services">-->
<!--               <p>Mobile / DTH Recharge</p>-->
<!--               <p>With Points<span>4%</span></p>-->
<!--               <p>Without Points<span>1%</span></p>-->
<!--               <p><img src="{{asset('mtheme1/assets/images/recharge.png')}}" class="recharge_img"></p>-->
<!--            </div>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->
<!--</section>-->
<!-- Why Choose Us Section Starts Here -->
<section class="choose-us padding-top padding-bottom" id="about" style="background:#fff;">
   <div class="container">
      <div class="row align-items-center">
         <div class="col-lg-4 ">
            <div class="section__thumb rtl">
               <img src="{{asset('mtheme1/assets/images/bbeing.png')}}" class="image_mobile" alt="choose-us">
            </div>
         </div>
         <div class="col-lg-8">
            <div class="choose__us__content">
               <div class="section__header mb-0">
                  <h2 class="section__header-title">About {{env('APP_NAME','')}} </h2>
                  <p>Welcome to {{env('APP_NAME','')}}, where we believe in the power of seamless connectivity. We understand that staying connected is not just a necessity but a lifeline in today's fast-paced world. Our mission is to empower individuals by providing a user-friendly platform for quick and secure mobile and DTH recharges.</p>
                 
                  <div class="paymet_data">
                     <span><i class="fa-solid fa-check"></i> All-in-One Payment App
                     </span>
                     <span><i class="fa-solid fa-check"></i> Effective Cashback Offers
                     </span>
                     <span><i class="fa-solid fa-check"></i> Transparent and Reliable
                     </span>
                     <span><i class="fa-solid fa-check"></i>  Advanced Features
                     </span>
                   
                     <span><i class="fa-solid fa-check"></i>  100% Secure and Reliable
                     </span>
                     <span><i class="fa-solid fa-check"></i>  Trusted, Safe & Secure
                     </span>
                  </div>
                  <ul>
                     <li><b>Reliability</b>: Count on us for quick and reliable recharges whenever you need them.</li>
                     <li><b>Transparency</b>: We believe in transparent transactions. No hidden fees, no surprises—just straightforward and honest service.</li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="three_data padding-top padding-bottom ">
   <div class="container">
        <div class="row">
         <div class="col-lg-12">
            <div class="section__header text-center max-p">
               <h2 class="section__header-title">Our Services</h2>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="three_option_solution">
                  <img src="{{asset('mtheme1/assets/images/recharge-bill-payment.png')}}">
               <h4>Recharge & Bill Payment</h4>
               <p>Mobile, DTH, Fastag, Electricity, LPG Bill, Water, Landline.</p>
              </div>
         </div>
         <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="three_option_solution">
                  <img src="{{asset('mtheme1/assets/images/travel-services.png')}}">
               <h4>Travel Services</h4>
               <p>Hotel, Bus and Flight</p>
              </div>
         </div>
         <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="three_option_solution">
                <img src="{{asset('mtheme1/assets/images/recharge-bill-payment.png')}}">
               <h4>E-Commerce</h4>
               <p>Grocery, Vegetable, Electronic, Clothes</p>
              </div>
         </div>
         <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="three_option_solution">
                    <img src="{{asset('mtheme1/assets/images/create-post.png')}}">
               <h4>Create Post</h4>
               <p>Like, Comment, Share</p>
              </div>
         </div>
      </div>
   </div>
</section>
<!-- Transection Section Ends Here -->
<!-- Referral Section Starts Here -->
<!-- Download Section Starts Here -->
<section class="download-section padding-top padding-bottom section-bg-two overflow-hidden" id="vision" style="background: #fff;">
   <div class="container">
      <div class="row align-items-center">
         <div class="col-lg-7">
            <div class="section__header">
               <h2 class="section__header-title">How Does It Work ?</h2>
               <div class="service_market">
                   
                  <h4>Explore</h4>
                  <p>Discover the businesses near you offering discounts and cashback</p>
               </div>
               <div class="service_market">
                  <h4>Pay
                  </h4>
                  <p>pay seamlessly through the Useme app</p>
               </div>
               <div class="service_market">
                  <h4>Enjoy</h4>
                  <p>Enjoy discounts and cashback on each payment </p>
               </div>
               <!--<h2 class="section__header-title">Our Mission</h2>
                  <p>Our mission is to build a {{env('APP_NAME')}} community which is very familiar with digital assets throughout the country to use the {{env('APP_NAME')}} coin as a utility coin.</P>-->
            </div>
            <!--<div class="button__wrapper">
               <a href="" class="cmn--btn download-btn"><div class="icon"><i class="fab fa-google-play"></i></div> <div class="text"><p>DOWNLOAD</p><p class="for">For Android</p></div></a>
               <a href="" class="cmn--btn download-btn"><div class="icon"><i class="fab fa-apple"></i></div> <div class="text"><p>DOWNLOAD</p><p class="for">For IOS</p></div></a>
               </div>-->
            <div class="counter__wrapper row gy-4 gy-sm-5 pt-4 pt-sm-5">
            </div>
         </div>
         <div class="col-lg-5 ">
            <div class="section__thumb profit__calculation__thumb ">
               <img src="{{asset('mtheme1/assets/images/slashing-hatred.png')}}" class="download_imge"  alt="download">
               <div class="shapes">
                  <!--<img src="{{asset('mtheme1/assets/images/referral/clock.png')}}" alt="referral" class="shape shape1">-->
                  <!--<img src="{{asset('mtheme1/assets/images/referral/man.png')}}"  height="250px" alt="referral" class="shape shape2">-->
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<!-- Faq Section Starts Here -->
<section class="faq-section padding-top padding-bottom bg_img"  id="faq" >
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-7 col-md-10">
            <div class="section__header text-center max-p">
               <h2 class="section__header-title">Facts & Questions</h2>
               <p>We answer some of your Frequently Asked Questions regarding our platform. If you have a query that is not answered here, Please contact us.</p>
            </div>
         </div>
      </div>
      <div class="row justify-content-center">
         <div class="col-xl-8 col-lg-10">
            <div class="faq__wrapper">
               <div class="faq__item">
                  <div class="faq__item-title">
                     <h4 class="title">What services does {{env('APP_NAME')}} offer </h4>
                  </div>
                  <div class="faq__item-content">
                     <p>{{env('APP_NAME')}} offers mobile and DTH recharge services. You can recharge prepaid and postpaid mobile connections and pay for DTH services through our platform.</p>
                  </div>
               </div>
               <div class="faq__item open active">
                  <div class="faq__item-title">
                     <h4 class="title">How do I recharge my mobile or DTH account on {{env('APP_NAME')}} ?</h4>
                  </div>
                  <div class="faq__item-content">
                     <p>Recharging is easy! Select the service you want to recharge, enter your account details, choose the recharge amount, and complete the transaction through our secure payment gateway.</p>
                  </div>
               </div>
               <div class="faq__item">
                  <div class="faq__item-title">
                     <h4 class="title"> What payment methods are accepted on {{env('APP_NAME')}} ? </h4>
                  </div>
                  <div class="faq__item-content">
                     <p>{{env('APP_NAME')}} accepts a variety of payment methods, including credit/debit cards, net banking, UPI, and digital wallets. Choose the method that suits you best for a hassle-free transaction.</p>
                  </div>
               </div>
              
            </div>
         </div>
      </div>
   </div>
</section>


@endsection