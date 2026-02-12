<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- BootStrap Link -->
    <link rel="stylesheet" href="{{asset('mtheme1/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('mtheme1/assets/css/animate.css')}}">

    <!-- Icon Link -->
    <link rel="stylesheet" href="{{asset('mtheme1/assets/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('mtheme1/assets/css/line-awesome.min.css')}}">

    <!-- Plugings Link -->
    <link rel="stylesheet" href="{{asset('mtheme1/assets/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('mtheme1/assets/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('mtheme1/assets/css/odometer.css')}}">

    <!-- Custom Link -->
    <link rel="stylesheet" href="{{asset('mtheme1/assets/css/main.css')}}">
    <link rel="shortcut icon" href="" type="image/x-icon">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <script src="https://cdn.ethers.io/lib/ethers-5.2.umd.min.js"></script>
    
    <title>{{env('APP_NAME')}}</title>
<style>
   @media screen and (max-width: 768px) {
 h1.data {
    font-size: 70px;
 }
 .meta_detail {
   
    transform: translate(0%, 41%);
 }
}
    a.View_Detail {
   
    margin-bottom: 10px;
    padding: 4px 10px;
    font-size: 14px;
    font-weight: 600;
    color: #000;
    border-radius: 4px;
    background: linear-gradient(90deg,#de9f17 0%,#d19c15 33%,#fff58a 67%,#ffd147 100%);
}
.table thead tr th{
    font-size:16px;
    white-space: nowrap;
}
    .banner-section {
    
    padding-top: 150px;
    padding-bottom: 100px;
}
    .header__top__wrapper {
    border-bottom:none !important;
}
input.form-control.form--control {
    padding: 10px;
}
.alert.alert-danger.alert-dismissible {
    display: flex;
    align-items: center;
}

button.btn.cmn--btn.mt-4 {
    width: 100%;
}
button.close {
    padding: 9px;
    margin-right: 5px;
    line-height: 14px;
    background: #d7a31f;
    border: none;
    color: #fff;
    border-radius: 2px;
}

/* wallet-particulars-css-start */

.wallet_particulars {
    padding: 16px 16px;
    text-align: center;
    background: linear-gradient(180deg, #04083F 0%, rgb(6 10 64) 100%);
    margin: 10px 0px;
    border-radius: 6px;
    border: 3px solid #D7A31F;
}

.wallet_image img {
    width: 46px;
}

.wallet_content {
    padding-top: 10px;
}

.wallet_content h4 {
    font-size: 18px;
    text-transform: capitalize;
    color: #ffff;
    margin-bottom: 5px;
}

.wallet_content h3 {
    font-size: 18px;
    color: #d49d17;
    font-weight: 600;
}
h2.all_inclusive {
    font-size: 41px;
}
/* wallet-particulars-css-end */

/* invest_data */
input.cus_input {
    background: #1a2156;
    color: #fff;
    border-radius: 5px;
    border: 1px solid transparent;
    padding: 17px 15px;
    width: 100%;
    margin-bottom: 15px;
}

ul.trx_btn_data.mb_20 {
    overflow: hidden;
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
}

ul.trx_btn_data.mb_20 li {
    margin-right: 10px;
}

button.inactive_data {
    display: block;
    width: 100%;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 15px 8px;
    font-size: 13px;
    background: #1a2156;
}

.text_center_data {
    text-align: center;
}

button.join_now_btn_data {
    background: #d7a31f;
    color: #fff;
    border: 2px solid #d7a31f;
    padding: 10px 60px;
    border-radius: 5px;
    text-transform: capitalize;
}
.form_detail_data {
    margin-top: 10px;
}
/* invest_data_css */

/* get_detail_css */
h2.all_inclusive {
    font-size: 41px;
}

.get_your_detail {
    text-align: center;
    margin-bottom: 40px;
}

.get_invest_detail {
    padding: 16px 16px;
    text-align: center;
    background: linear-gradient(180deg, #04083F 0%, rgb(6 10 64) 100%);
    margin: 10px 0px;
    border-radius: 6px;
    border: 3px solid #D7A31F;
}

.get_invest_detail_image img {
    width: 60px;
}

.get_invest_detail_coment h4 {
    font-size: 18px;
    text-transform: capitalize;
    color: #ffff;
    margin-bottom: 5px;
}

.get_invest_detail_coment {
    padding-top: 15px;
}

.get_invest_detail_coment h3 {
    font-size: 18px;
    color: #d49d17;
    font-weight: 600;
}

/* global_blockchain_investment_css */


.global_blockchain_investment {
    padding: 16px 16px;
    text-align: center;
    background: linear-gradient(180deg, #04083F 0%, rgb(6 10 64) 100%);
    margin: 10px 0px;
    border-radius: 6px;
    border: 3px solid #D7A31F;
}

.global_blockchain_investment_content {
    padding-top: 15px;
}



.global_blockchain_investment_content h4 {
    font-size: 18px;
    text-transform: capitalize;
    color: #ffff;
    margin-bottom: 5px;
}

.global_blockchain_investment_content h3 {
    font-size: 18px;
    color: #d49d17;
    font-weight: 600;
}

.global_blockchain_investment_image img {
    width: 60px;
}


h4.community_status {
    padding-top: 10px;
    font-size: 32px;
    color: #dfaa27;
    margin: 0;
}

.community_level_data.teble-responsive table {
    width: 100%;
}

.community_level_data.teble-responsive {
    overflow-x: auto;
}

.staking_button a {
    border: 1px solid #d49d15;
    position: relative;
    z-index: 1;
    padding: 8px 35px;
    background: linear-gradient(90deg, #de9f17 0%, #d19c15 33%, #fff58a 67%, #ffd147 100%);
    color: #000;
    font-weight: 600;
    text-transform: capitalize;
    border-radius: 4px;
}

.staking_button {
    margin-top: 15px;
}
.community_level_data.teble-responsive table thead tr th {
    background: linear-gradient(180deg, #F6C94A 19%, #D39B15 81%);
    padding: 10px 20px;
    font-family: "Poppins", sans-serif;
    color: #1f1f23;
    border: none;
    font-size: 18px;
    font-weight: 600;
    white-space: pre;
}
.community_level_data.teble-responsive table tbody tr td {
background: #1A1A33;
    padding: 10px 20px;
    color: #fff;
    vertical-align: middle;
}
.alert-message span {
    margin-left: 5px;
}
.tab_matic_button {
    display: flex;
    align-items: center;
    justify-content: end;
}
.get_invest_detail_coment h3 a {
    color: #d7a31f;
   /* border: 1px solid #d7a31f;
    padding: 4px 10px;
    border-radius: 4px;  */
}
.alert.alert-success.alert-dismissible {
    display: flex;
    align-items: center;
}
.staking_button button{
    background: linear-gradient(90deg, #de9f17 0%, #d19c15 33%, #fff58a 67%, #ffd147 100%);
    border:none;
}
li.sponoser_button button {
    background: linear-gradient(90deg, #de9f17 0%, #d19c15 33%, #fff58a 67%, #ffd147 100%);
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    color: #1f1f23;
    position: relative;
    z-index: 1;
    font-size: 18px;
    font-weight: 600;
    text-transform: capitalize;
   
}

ul.data_detail_sponser {
    display: flex;
}

li.sponoser_button {
    margin-left: 10px;
}

@media only screen and (max-width: 768px) {
    .table tbody tr td{
    font-size: 14px;
}
.community_level_data.teble-responsive table thead tr th {
    background: linear-gradient(180deg, #F6C94A 19%, #D39B15 81%);
    padding: 4px 14px;
    font-size: 14px;
    white-space: nowrap;
}
.community_level_data.teble-responsive table tbody tr td{
    font-size: 14px;
}
ul.data_detail_sponser {
    flex-direction: column;
}
li.sponoser_button {
    margin-left: 0px;
}
span.wallet {
    white-space: nowrap;
}
li.sponoser_button button{
    padding: 8px 20px;
}
}

button.sponser_submit_button {
    margin-top: 10px;
    padding: -3px;
    padding: 5px 10px !important;
}


input.form-control {
    padding: 10px;
}

.form_sponser label {
    color: #00000e;
    text-transform: capitalize;
    font-size: 14px;
    margin-bottom: 5px;
}

button.close.data {
    padding: 6px;
    line-height: -4px;
}

button.close.data span {
    line-height: 12px !important;
}

button.close.data {
    padding: 10px;
}

/* tab-content */

@media (max-width: 991px){
.table tbody tr td {
    display: block;
}
.table tbody tr:last-child td:first-child {
    border-radius: 0 0 0 0px;
}
.table tbody tr:last-child td:last-child {
     border-radius: 0 0 0px 0; 
}
.tab-content {
    overflow-x: auto;
}
td.no_data {
    white-space: nowrap;
}
td.data_s {
    white-space: nowrap;
}
}


.btn-info:hover {
    color: #000;
    background-color: #2faafa;
    border-color: #2faafa;
}
.earn_refer_title {
    padding: 25px 25px;
    border: 3px solid #D7A31F;
    box-shadow: 0 0 0 2px rgb(215 163 31 / 70%);
    border-radius: 12px;
   margin-bottom:40px;
    background: linear-gradient(180deg, #04083F 0%, rgba(0, 0, 28, 0.7) 100%);
}

input#referral_link_right {
    border-radius: 4px;
    padding: 6px;
    border: none;
}
   /*                   */

     section.banner-section.overflow-hidden {
       
        position: relative;
    }
    
    
    video.vedio_tag {
        position: absolute;
        bottom: 0;
    }
    
    
    
    .bg-video-wrap {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 614px;
    }
    video {
      width:100%;
      z-index: 1;
    }
    
    .meta_detail {
      text-align: center;
      color: #fff;
      position: absolute;
      top: 0px;
        bottom: 0;
        transform: translate(0%, 31%);
      bottom: 0;
      left: 0;
      right: 0;
     
      z-index: 3;
     
      width: 100%;
     
    }
    .bg-video-wrap-detail {
      position: relative;
      overflow: hidden;
      width: 100%;
      height:500px;
     
    }
    .meta_detail_detail h4 {
        font-size: 50px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .meta_detail_detail p {
        font-size: 18px;
    }
    .meta_detail_detail {
      text-align: center;
      color: #fff;
      position: absolute;
      top: 36%;
      bottom: 0;
      left: 0;
      right: 0;
     
      z-index: 3;
     
      width: 100%;
     
    }
    h1.data {
        font-size: 100px;
        color: #e1a620;
        font-weight: 900;
        font-style: italic;
        text-align: initial;
        line-height: 88px;
    }
    
    .content_meta a {
        background: #e1a620;
        padding: 6px 59px;
        font-size: 20px;
        color: #fff;
        text-transform: capitalize;
        display: inline-block;
        /* max-width: 400px; */
        border-radius: 40px;
        font-weight: 700;
    }
    
    
    
    .content_meta p {
        margin-bottom: 10px;
        font-weight: 600;
        text-align: initial;
    }
    .banner-section{
        background: none !important;
        
         padding:0px !important;
    }
    
    .three_option {
        padding: 12px;
        border: 1px solid #d7a31f;
        border-radius: 20px;
        display: flex;
        align-items: center;
        min-height: 103px;
        margin-bottom:20px;
    }
    
    .meta_detail_detail p {
       
        margin-bottom: 15px;
    }
    .three_option img {
        width: 50px;
        margin-right: 10px;
    }
    .images_transection {
        background: #d7a31f;
        padding: 13px;
        border-radius: 15px;
    }
    a.readmore_detail {
        background: #e1a620;
        padding: 6px 35px;
        font-size: 20px;
        color: #fff;
        text-transform: capitalize;
        display: inline-block;
        
        border-radius: 40px;
        font-weight: 700;
    }
    
    .three_option p {
        font-size: 15px;
        line-height: 20px;
    }
    
    
    .transection_image_data {
        display: flex;
        align-items: center;
    }
    
    .transection_image_data {
        margin-bottom: 20px;
    }
    
    .images_transection {
        margin-right: 20px;
    }
    
    .images_transection img {
        width:50px;
    }
    
    
    
    
    
    
    .content_meta.detail img {
         width: 100%; 
      
      max-width: 350px;
    }
    .feature__item-icon img {
        width: 45px;
        display: block;
        margin: auto;
    }
    
    .content_meta p {
       
        font-size: 22px;
    }
    .feature__item-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 15px;
        background: #d7a31f;
        border-radius: 12px;
        text-align: center;
    }
    video.vedio_text1 {
        display: none;
    }
    
    @media screen and (max-width: 1132px) {
        .content_meta img {
        display: none;
    }
    }
    @media screen and (max-width: 768px) {
        .meta_detail_detail {
           top:100px;
        }
        .meta_detail_detail h4 {
        font-size: 32px;
    }
    .meta_detail_detail p {
        font-size: 15px;
        padding: 10px 10px;
    }
    }
    @media screen and (max-width: 600px) {
        .meta_detail_detail {
           top:92px;
        }
        .meta_detail_detail h4 {
        font-size: 32px;
    }
    .meta_detail_detail p {
        font-size: 15px;
        padding: 10px 10px;
    }
    video.vedio_text {
        display: none;
    }
    video.vedio_text1 {
        display: block !important;
    }
    
    }
    
    
    @media all and (max-width: 768px) and (min-width: 567px) {
        h1.data {
        font-size: 60px;
      }
      .meta_detail {
        transform: translate(0%, 132px);
      }
    }
    
    @media all and (max-width: 566px) and (min-width: 567px) {
        h1.data {
        font-size: 60px;
      }
      .meta_detail {
        transform: translate(0%, 132px);
      }
    }



/* roadmap */

.section__thumb.rtl img {
    width: 100%;
}
.timeline{
  position:relative;
  margin:50px auto;
  padding:40px 0;
  width:1000px;
  box-sizing:border-box;
}
.timeline:before{
  content:'';
  position:absolute;
  left:50%;
  width:2px;
  height:100%;
  background:#c5c5c5;
}
.timeline ul{
  padding:0;
  margin:0;
}
.timeline ul li{
  list-style:none;
  position:relative;
  width:50%;
  padding:20px 40px;
  box-sizing:border-box;
}
.timeline ul li:nth-child(odd){
  float:left;
  text-align:right;
  clear:both;
}
.timeline ul li:nth-child(even){
  float:right;
  text-align:left;
  clear:both;
}
.content{
  padding-bottom:20px;
}
.timeline ul li:nth-child(odd):before
{
  content:'';
  position:absolute;
  width:10px;
  height:10px;
  top:24px;
  right:-6px;
  background:rgb(215 163 31);
  border-radius:50%;
  box-shadow:0 0 0 3px rgb(215 163 31);
}
.timeline ul li:nth-child(even):before
{
  content:'';
  position:absolute;
  width:10px;
  height:10px;
  top:24px;
  left:-4px;
 background:rgb(215 163 31);
  border-radius:50%;
   box-shadow:0 0 0 3px rgb(215 163 31);
}
.timeline ul li h3{
  padding:0;
  margin:0;
  color:rgba(233,33,99,1);
  font-weight:600;
}
.timeline ul li p{
  margin:10px 0 0;
  padding:0;
}
.timeline ul li .time h4{
  margin:0;
  padding:0;
  font-size:14px;
}
.timeline ul li:nth-child(odd) .time
{
  position:absolute;
  top:12px;
  right:-165px;
  margin:0;
  padding:8px 16px;
 background: rgb(215 163 31);
  color:#fff;
  border-radius:18px;
  box-shadow:0 0 0 3px rgb(215 163 31);
}
.timeline ul li:nth-child(even) .time
{
  position:absolute;
  top:12px;
  left:-165px;
  margin:0;
  padding:8px 16px;
  background:#D7A31F;
  color:#fff;
  border-radius:18px;
  box-shadow:0 0 0 3px #D7A31F;
}
@media(max-width:1000px)
{
  .timeline{
    width:100%;
  }
}
@media(max-width:767px){
  .timeline{
    width:100%;
    padding-bottom:0;
  }
  .section__thumb.profit__calculation__thumb img {
    max-width: 100%;
}
  h1{
    font-size:40px;
    text-align:center;
  }
  .timeline:before{
    left:20px;
    height:100%;
  }
  .timeline ul li:nth-child(odd),
  .timeline ul li:nth-child(even)
  {
    width:100%;
    text-align:left;
    padding-left:50px;
    padding-bottom:50px;
  }
  .timeline ul li:nth-child(odd):before,
  .timeline ul li:nth-child(even):before
  {
    top:-18px;
    left:16px;
  }
  .timeline ul li:nth-child(odd) .time,
  .timeline ul li:nth-child(even) .time{
    top:-30px;
    left:50px;
    right:inherit;
  }
}



.main-top{
    padding-bottom:10px;
    
    
}
    </style>
    
    @stack('style')
    
    
</head>
<body>

    <!--<div class="preloader">
        <div class="preinnner">
            <div class="ring"></div>
            <div class="ring"></div>
            <div class="ring"></div>
        </div>
    </div>-->
    <div class="overlay"></div>

    <!-- Header Section Starts Here -->
    @include('layouts.mtheme1.header')
    <!-- Header Section Ends Here -->
    @yield('content')

    <!-- Search Form Starts Here -->
    <div class="search__form__wrapper">
        <div class="form__inner">
            <form class="search__form">
                <div class="form-group">
                    <input type="text" class="form-control form--control" placeholder="Search Here...">
                    <button type="submit" class="cmn--btn btn">Search</button>
                </div>
            </form>
            <button class="btn-close btn-close-white"></button>
        </div>
    </div>
    <!-- Search Form Ends Here -->

    <style>
        .scrollToTop i {
       transform: rotate(0deg) !important;
   }
    </style>
    <!-- Footer Section Starts Here -->
       @include('layouts.mtheme1.footer')
       
       <!-- <input id="ttt" type="text" value="23">  -->
   
       <a href="#0" class="scrollToTop"><i class="fa fa-arrow-up"></i></a>
       <script src="{{asset('mtheme1/assets/js/jquery-3.6.0.min.js')}}"></script>
       <script src="{{asset('mtheme1/assets/js/bootstrap.min.js')}}"></script>
       <script src="{{asset('mtheme1/assets/js/slick.min.js')}}"></script>
       <script src="{{asset('mtheme1/assets/js/nice-select.js')}}"></script>
       <script src="{{asset('mtheme1/assets/js/odometer.min.js')}}"></script>
       <script src="{{asset('mtheme1/assets/js/viewport.jquery.js')}}"></script>
       <script src="{{asset('mtheme1/assets/js/main.js')}}"></script>
                  
        
       <script type="text/javascript" src="https://unpkg.com/web3modal"></script>
       <script type="text/javascript" src="https://unpkg.com/evm-chains@0.2.0/dist/umd/index.min.js"></script>
       <script type="text/javascript" src="https://unpkg.com/@walletconnect/web3-provider"></script>
       <script type="text/javascript" src="https://unpkg.com/fortmatic@2.0.6/dist/fortmatic.js"></script>
       <script src="https://cdn.ethers.io/lib/ethers-5.1.umd.min.js" type="text/javascript"></script>
        @stack('scripts')
    
     
     </body>
   
   </html>