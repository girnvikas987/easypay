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
   
    font-size: 47px;
}
    .container.data {
        position: absolute;
        z-index: 99999;
        top: 102px;
    }

.paymet_data span i {
    font-size: 18px;
    color: #5f94e9;
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
    color: #fff;
}

.paymet_data {
    margin: 20px 0px;
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
    margin-bottom:10px;
    border-radius: 10px;
}

.three_option_solution h4 {
    text-align: center;
    margin-bottom: 14px;
}

.three_option_solution p {
    font-size: 16px;
}

.three_option_solution p b {
    color: #fff;
}
.service_market h4 {
    font-size: 13px;
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
    color: #fff;
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
                    <h1 class="title">{{env('APP_NAME')}} Empowering Connectivity through Seamless Recharge Solutions</h1>
                    <p>{{env('APP_NAME')}} is transforming the recharge and payment option with our user-friendly platform that offers seamless recharge & payment solutions. We prioritize reliability, providing quick and dependable recharges and payment option without any hidden fees. With multiple payment options and bank-grade security, we make recharging effortless and secure. Join us on this exciting journey to empower connectivity and be part of the future of recharge services. Invest in {{env('APP_NAME')}} and revolutionize the way people stay connected.
</p>
					<a href="{{asset('mtheme1/assets/images/s2pay.apk')}}" class="cmn--btn" download>Download Android APP</a>
                </div>
                <div class="banner__thumb d-none d-lg-block">
                    <img src="{{asset('mtheme1/assets/images/54kqVc1cG.png')}}" alt="banner">
                   
                </div>
            </div>
        </div>
    </section>
        
         <section class="three_data padding-top ">
        <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="three_option">
                            <!--<img src="{{asset('mtheme1/assets/images/meta_1.png')}}" alt="choose-us">-->
                            <h4>Vision
</h4>
                            <p>To be the go-to platform for seamless and rewarding financial transactions, offering a wide range of payment options and cashback rewards for every user.
</p>
                         </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="three_option">
                            <!--<img src="{{asset('mtheme1/assets/images/meta_2.png')}}" alt="choose-us">-->
                             <h4>Mission
</h4>
                            <p>To empower individuals with a versatile and user-friendly app, providing a convenient and secure avenue for all types of payments, while ensuring a consistent and enticing cashback program.


                           
                            </p>
                         </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="three_option">
                            <!--<img src="{{asset('mtheme1/assets/images/meta_3.png')}}" alt="choose-us">-->
                            <h4>Value
</h4>
                            <p>We are committed to providing a comprehensive and rewarding financial experience, with transparency, reliability, and exclusive cashback offers on every transaction, making {{env('APP_NAME')}} the preferred choice for all payment needs.


                          
                            </p>
                         </div>
                    </div>
    
    
                
            </div>
        </div>
    </section>
    
    
    
    
     <section class="choose-us padding-top" id="">
            <div class="container">
                <div class="row">
                   <div class="col-lg-12">
                        <div class="section__header text-center max-p">
                            <h2 class="section__header-title">Empowering Your Financial Decision-Making</h2>
                         </div>
                    </div>
                 </div>
                 <div class="row">
                     <div class="col-12">
                         <div class="mobile_services">
                            <p>Mobile / DTH Recharge</p>
<p>With Points<span>4%</span></p>
<p>Without Points<span>1%</span></p>
<p><img src="{{asset('mtheme1/assets/images/recharge.png')}}" class="recharge_img"></p>

                         </div>
                     </div>
                 </div>
            </div>
        </section>
    
        <!-- Why Choose Us Section Starts Here -->
        <section class="choose-us padding-top padding-bottom" id="about">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 ">
                        <div class="section__thumb rtl">
                            <img src="{{asset('mtheme1/assets/images/mobile11.jpg')}}" class="image_mobile" alt="choose-us">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="choose__us__content">
                            <div class="section__header mb-0">
                                <h2 class="section__header-title">About {{env('APP_NAME','')}} </h2>
                                <p>Welcome to {{env('APP_NAME','')}}, where we believe in the power of seamless connectivity. We understand that staying connected is not just a necessity but a lifeline in today's fast-paced world. Our mission is to empower individuals by providing a user-friendly platform for quick and secure mobile and DTH recharges.</p>
                                  <h6>
                                    Experience Seamless Connectivity with {{env('APP_NAME')}}: Your Trusted Recharge Companion

                                      
                                  </h6>
                                  <div class="paymet_data">
                                  <span><i class="fa-solid fa-check"></i> All-in-One Payment App
</span>
                                  <span><i class="fa-solid fa-check"></i> Effective Cashback Offers
</span>
                                  <span><i class="fa-solid fa-check"></i> Transparent and Reliable
</span>
                                  <span><i class="fa-solid fa-check"></i>  Advanced Features
</span>
                                  <span><i class="fa-solid fa-check"></i>  BBPS Integration

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
         
         
          <section class="three_data padding-bottom ">
        <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="three_option_solution">
                           <h4>Problem</h4>
                           <p><b>Limited Payment Options:</b>Many users face the challenge of limited payment methods for mobile and DTH recharges.</p>
                           <p><b>Lack of Transparency:</b>Users encounter hidden fees and surprise charges during the recharge process.</p>
                           <p><b>Inconvenient Recharge Processes:</b>The current recharge methods are not seamless and user-friendly, leading to frustration.</p>
                           
                         </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="three_option_solution">
                             <h4>Solution</h4>
                           <p><b>Diverse Payment Methods:</b>We offers a variety of payment options including credit/debit cards, net banking, UPI, and digital wallets.</p>
                           <p><b>Transparency:</b>We believe in transparent transactions with no hidden fees or surprises, ensuring straightforward and honest service.</p>
                           <p><b>User-Friendly Platform:</b>Providing user-friendly platform for quick and secure mobile and DTH recharges, making the process effortless and convenient for users.</p>
                           

                           
                            
                         </div>
                    </div>
                    
                
            </div>
        </div>
    </section>
        <!-- Transection Section Ends Here -->
        <!-- Referral Section Starts Here -->
       
       
        <!-- Download Section Starts Here -->
        <section class="download-section padding-bottom section-bg-two overflow-hidden" id="vision">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="section__header">
                            <h2 class="section__header-title">Market Size
</h2>
<div class="service_market">
<h4>Total Addressable Market 
</h4>
<span>USD 312.92 billion
</span>
<p>The Global Fintech Market size is estimated at USD 312.92 billion in 2024, and is expected to reach USD 608.35 billion by 2029, growing at a CAGR of greater than 14% during the forecast period (2024-2029).
</p>
<h6>https://www.mordorintelligence.com/industry-reports/global-fintech-market#:~:text=The%20Global%20Fintech%20Market%20size%20is%20estimated%20at,greater%20than%2014%25%20during%20the%20forecast%20period%20%282024-2029%29</h6>
         
         </div>   
         
         <div class="service_market">
<h4>Service Addressable Market 
 
</h4>
<span>USD 111.14 billion

</span>
<p>The India Fintech Market size is estimated at USD 111.14 billion in 2024, and is expected to reach USD 421.48 billion by 2029, growing at a CAGR of 30.55% during the forecast period (2024-2029).

</p>
<h6>https://www.mordorintelligence.com/industry-reports/india-fintech-market/market-size#:~:text=The%20India%20Fintech%20Market%20size%20is%20estimated%20at,CAGR%20of%2030.55%25%20during%20the%20forecast%20period%20%282024-2029%29.
</h6>
         
</div>  
         <div class="service_market">
<h4>Service Obtainable Market 
</h4>

<p>We Aim to capture 0.1% of SAM which is approximately valued at USD 0.11 billion. 
</p>

         
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
                            <img src="{{asset('mtheme1/assets/images/mobil14.jpg')}}" class="download_imge"  alt="download">
                              <div class="shapes">
                                <!--<img src="{{asset('mtheme1/assets/images/referral/clock.png')}}" alt="referral" class="shape shape1">-->
                                <!--<img src="{{asset('mtheme1/assets/images/referral/man.png')}}"  height="250px" alt="referral" class="shape shape2">-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Download Section Ends Here -->
       
     <section class="choose-us padding-top" id="">
            <div class="container">
                <div class="row">
                   <div class="col-lg-12">
                        <div class="section__header text-center max-p">
                            <h2 class="section__header-title">Competitive Analysis

</h2>
                         </div>
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-12">
                        <div class="competitive_data">
                            <table>
                                <tr>
                                <th>Feature</th>
                                <th>{{env('APP_NAME')}}</th>
                                <th>Youteg</th>
                                <th>BCP India
</th>
</tr>
<tr>
    <td class="secuit_d">Primary Focus
</td>
    <td>All-in-one Payments

</td>
    <td>Mobile Recharge

</td>
    <td>Digital Payment Platform

</td>
</tr>
<tr>
    <td class="secuit_d">
        Cashback

    </td>
    <td>
       High cashback on every transaction

    </td>
    <td>
        Limited cashback options

    </td>
    <td>
       Cashback program unclear

    </td>
</tr>
<tr>
    <td class="secuit_d">
        Security


    </td> 
    <td>
        Trusted, High security

    </td>
    <td>
        High security


    </td>
    <td>
        High security


    </td>
</tr>
                            </table>
                        </div>
                    </div>
                 </div>
            </div>
        </section>
        <!-- Faq Section Starts Here -->
        <section class="faq-section padding-top padding-bottom bg_img" style="background: url({{asset('mtheme1/assets/images/faq/bg.png')}});" id="faq">
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
                            <!--<div class="faq__item open active">
                                <div class="faq__item-title">
                                    <h4 class="title"> Can the trading program be trusted?</h4>
                                </div>
                                <div class="faq__item-content">
                                    <p>The Expert Advisors are extremely stable and reliable. However, you should still check it from time to time.</p>
                                </div>
                            </div>-->
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Faq Section Ends Here -->
    
    
    
<!--      <section class="choose-us padding-top" id="">-->
<!--            <div class="container">-->
<!--                <div class="row">-->
<!--                   <div class="col-lg-12">-->
<!--                        <div class="section__header text-center max-p">-->
<!--                            <h2 class="section__header-title">Founders-->

<!--</h2>-->
<!--                         </div>-->
<!--                    </div>-->
<!--                 </div>-->
<!--                 <div class="row">-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="competitive_team">-->
<!--                          <div class="team_data">-->
                           
<!--                              <div class="team_members">-->
<!--                                  <h5>Name: Surender Kumar -->
<!--</h5>-->
<!--<h5>Founder</h5>-->
<!--<h5>Post Graduation -->
<!--</h5>-->
<!--<h5>15 years of experience in banking -->
<!--</h5>-->
<!--                              </div>-->
<!--                          </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="competitive_team">-->
<!--                          <div class="team_data">-->
                            
<!--                              <div class="team_members">-->
<!--                                  <h5>Name: Kavita Devi-->

<!--</h5>-->
<!--<h5>Director </h5>-->
<!--<h5>Higher secondary -->

<!--</h5>-->
<!--<h5>5 Years of experience in insurance -->

<!--</h5>-->
<!--                              </div>-->
<!--                          </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="competitive_team">-->
<!--                          <div class="team_data">-->
                            
<!--                              <div class="team_members">-->
<!--                                  <h5>Name: Amanpreet Singh-->

<!--</h5>-->
<!--<h5>Developer </h5>-->

<!--<h5>10 Years of experience in Developing -->

<!--</h5>-->
<!--                              </div>-->
<!--                          </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                 </div>-->
<!--            </div>-->
<!--        </section>-->
    
    
    
    
        <section class="ownload-section  section-bg-two overflow-hidden">
        <div class="bg-video-wrap-detail">
            <!--<video src="{{asset('mtheme1/assets/images/stock_data.mp4')}}" class="vedio_text" loop muted autoplay></video>-->
            <!--<video src="{{asset('mtheme1/assets/images/Sequence_01.mp4')}}" class="vedio_text1" loop muted autoplay>-->
            <!--</video>-->
            <div class="meta_detail_detail">
                <h4>Trusted, Safe & Secure</h4>
                <p>Bank Grade Security, Fully Encrypted, 24X7 Customer Support, App Lock</p>
                <!--<a href="{{route('register')}}" class="readmore_detail">Download android APP</a>-->
            </div>
        </div>
    </section>
@endsection