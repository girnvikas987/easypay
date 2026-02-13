<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\FundRequestController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\EbikeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BaseController;
use App\Helper\Distribute;
use App\Helper\RoiDistribute;
use App\Http\Controllers\BuySellController;
use App\Http\Controllers\EliteController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\FlyController;
use App\Http\Controllers\GoldController;
use App\Http\Controllers\LuckyDrawController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\DonationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('fetch_referral', [BaseController::class, 'fetchReferral']);
Route::post('callBack_scanPay', [WithdrawalController::class, 'callBack_ScanPay']);
Route::post('callBack_Payout', [WithdrawalController::class, 'callBack_Payout']);
Route::post('callBack_click_Payout', [WithdrawalController::class, 'callBack_Click_Payout']);
Route::post('callBack_New_Click_Payout', [WithdrawalController::class, 'callBack_New_Click_Payout']);
Route::post('callBack_payIn', [FundRequestController::class, 'callBack_payIn']);
Route::any('updateTimer', [GameController::class, 'updateGameTimer']);
// Route::any('callback', [FundRequestController::class, 'callback_gateway'])->name('phoneGateway');
Route::any('callback_fund', [FundRequestController::class, 'callBackFundRequest']);
Route::get('callback_recharge', [RechargeController::class, 'callback_recharge']);
Route::get('meeting', [MeetingController::class, 'getMeeting']);
Route::get('gallery', [GalleryController::class, 'getGallery']);
Route::post('notification', [GalleryController::class, 'notify']);
Route::post('generate_otp', [OtpController::class, 'generateOtp']);
Route::post('forgot_password', [PasswordController::class, 'generateNewPassword']);
Route::post('forgot_mpin', [PasswordController::class, 'generateNewMpin']);
Route::get('get_app_version', [AppController::class, 'getVersion']);
Route::post('test_gold', [GoldController::class, 'test']);
Route::get('roiincmcls',function(){
        return RoiDistribute::roiClosing();
});
Route::get('roifourincmcls',function(){
        return RoiDistribute::roiFourClosing();
});
Route::get('dailyincmcls',function(){
        return Distribute::clearDailyInmcome();
});
Route::get('royalty_distribution',function(){
        return Distribute::RoyaltyDistribution();
});
Route::get('royalty_recharge_distribution',function(){
        return Distribute::RoyaltyRechargeDistribution();
});
Route::get('loan_pay',function(){
        return Distribute::loanDistribution();
});
Route::get('is_Ebike_eligible',function(){
        return Distribute::IsEbikeEligible();
});
Route::get('is_recharge_trip_eligible',function(){
        return Distribute::IsRechargeTripEligible();
});

Route::get('fetch_gold_live',function(){
        return Distribute::fetchgold();
});

Route::get('Jack_pot_distribution',function(){
        return Distribute::JackpotDistribution();
});
Route::get('Jack_pot_weekly_distribution',function(){
        return Distribute::JackpotWeeklyDistribution();
});
Route::get('anything_testing',function(){
        return Distribute::testAnyThing();
});
// Route::get('Refferal_Income',function(){
//         return Distribute::RefferalIncome();
// });
Route::get('jackpot_users',function(){
        return Distribute::joinJackpotDrawUsers();
});
Route::get('jackpot_users_weekly',function(){
        return Distribute::joinJackpotDrawUsersWeekly();
});

Route::get('ebike_daily_income',function(){
        return Distribute::ebikeDailyIncome();
});
Route::get('distrbute_ebike_binary_macth',function(){
        return Distribute::distrbuteEbikeBinaryMacth();
});

Route::get('distrbute_ebike_binary_income',function(){
        return Distribute::distrbuteEbikeBinaryIncome();
});
Route::get('tour_daily_income',function(){
        return Distribute::tourDailyIncome();
});
Route::get('distrbute_tour_binary_macth',function(){
        return Distribute::distrbuteTourBinaryMacth();
});
Route::get('elite_daily_income',function(){
        return Distribute::eliteDailyIncome();
});
Route::get('elite_daily_new_income',function(){
        return Distribute::eliteDailynewIncome();
});
Route::get('distrbute_elite_binary_macth',function(){
        return Distribute::distrbuteEliteBinaryMacth();
});
Route::get('distrbute_elite_binary_income',function(){
        return Distribute::distrbuteEliteBinaryIncome();
});
Route::get('clr_withdraw_request',function(){
        return Distribute::clrRequest();
});
Route::get('make_handle_callback', [FundRequestController::class, 'handle']);


 Route::post('/payment/handle_webhook', [FundRequestController::class, 'handlewebhook']);
Route::get('call_recharge', [RechargeController::class, 'call_recharge']);
Route::get('call_razorpay', [PaymentController::class, 'verify']);
Route::middleware('auth:sanctum')->group(function () {









    /////////////////////////profile ///////////////////////////////////////////////
    Route::post('update_profile', [ProfileController::class, 'updateProfile']);
    Route::get('user', [ProfileController::class, 'data']);
    Route::post('validateUser', [ProfileController::class, 'validateUser']);
    Route::post('upload_image', [ProfileController::class, 'updateImage']);
    Route::post('verify_both_Otp', [ProfileController::class, 'verifyBothOtp']);
    Route::post('verify_pan_details', [ProfileController::class, 'verifyPan']);
    Route::post('update_user_details', [ProfileController::class, 'updateUser']);




    /////////////////////////profile ///////////////////////////////////////////////

    Route::post('get_active_packages', [DashboardController::class, 'getActivePackages']);
    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    Route::post('check_pin', [DashboardController::class, 'checkPin']);
    Route::post('update_pin', [DashboardController::class, 'updatePin']);
    Route::get('test_dash', [DashboardController::class, 'test']);
    Route::get('get_sponsor_info', [DashboardController::class, 'getSponsorInfo']);
    Route::get('get_videos', [DashboardController::class, 'getVideos']);

    /////////////////////////////investment ///////////////////////////////////////////////////////
    Route::post('topup', [InvestmentController::class, 'topup_api']);
    Route::get('packages', [InvestmentController::class, 'packages']);
    Route::post('test', [InvestmentController::class, 'check']);
    Route::post('invest_history', [InvestmentController::class, 'investHistory']);
    Route::post('package_details', [InvestmentController::class, 'getPackageDetails']);
     /////////////////////////////commitee ///////////////////////////////////////////////////////
    Route::get('commitee_packages', [InvestmentController::class, 'commitee_packages']);
    Route::post('take_commitee', [InvestmentController::class, 'takeCommitee']);
     /////////////////////////////investment ///////////////////////////////////////////////////////
     /////////////////////////////Saving fund///////////////////////////////////////////////////////
     Route::get('saving_packages', [InvestmentController::class, 'saving_packages']);
    Route::post('take_saving', [InvestmentController::class, 'takeSavingFund']);
     /////////////////////////////Saving fund///////////////////////////////////////////////////////

     /////////////////////////////Gold investment ///////////////////////////////////////////////////////


     Route::get('gold_packages', [GoldController::class, 'packages']);
     Route::post('gold_packages_new', [GoldController::class, 'packagesNew']);
     Route::post('gold_topup', [GoldController::class, 'topup_api']);
     Route::post('get_gold_royalty', [GoldController::class, 'getGoldRoyalty']);




     /////////////////////////////Gold investment ///////////////////////////////////////////////////////


    Route::post('transactions', [TransactionController::class, 'all_transactions']);
    Route::post('income_history', [TransactionController::class, 'incomeHistory']);
    Route::post('today_income', [TransactionController::class, 'todayIncomeHistory']);

    //////////////////////////fund Requets .////////////////////////////////////////////////////
    Route::get('fund_data', [FundRequestController::class, 'fund_request_details']);
    Route::get('add_fund', [FundRequestController::class, 'addFund']);
    Route::post('fund_request', [FundRequestController::class, 'addFundRequest']);
    Route::post('fund_request_gateway', [FundRequestController::class, 'fund_request_gateway']);
    Route::post('user_cancelled', [FundRequestController::class, 'userCancelled']);
    Route::post('approve_fund_request', [FundRequestController::class, 'approveFundRequest']);
    Route::post('fund_history', [FundRequestController::class, 'fundHistory']);
    Route::post('fund_request_simple', [FundRequestController::class, 'fund_request']);


    Route::post('make_request_order', [FundRequestController::class, 'makeOrder']);


    //////////////////////////fund Requets .////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////// Team /////////////////////////////////////////////////////////////////
    Route::post('team', [TeamController::class, 'getGeneration']);
    Route::post('direct', [TeamController::class, 'directs']);
     Route::post('team_history', [TeamController::class, 'getNewGeneration']);
     Route::post('team_historywithfilter', [TeamController::class, 'getNewGenerationWithFilter']);
    //////////////////////////////////////////////////////////////// Team /////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////// fund transfer /////////////////////////////////////////////////////////////////
    Route::post('fund_convert', [FundController::class, 'fundConvert']);
    Route::post('fund_transfer', [FundController::class, 'fundTransfer']);
    //////////////////////////////////////////////////////////////// fund transfer /////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////// reward /////////////////////////////////////////////////////////////////
    Route::post('get_reward', [RewardController::class, 'getReward']);
    Route::post('test_get_reward', [RewardController::class, 'TestgetReward']);
   //////////////////////////////////////////////////////////////// reward /////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////// Monthly Incentive  /////////////////////////////////////////////////////////////////
    Route::post('get_Month_Incentive', [RewardController::class, 'getMonthIncentive']);
    Route::post('get_loan', [RewardController::class, 'getLoan']);

   //////////////////////////////////////////////////////////////// Monthly Incentive /////////////////////////////////////////////////////////////////


   //////////////////////////////////////////////////////////////// password /////////////////////////////////////////////////////////////////
    Route::post('change_password', [PasswordController::class, 'updatePassword']);
  //////////////////////////////////////////////////////////////// password /////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////// kyc /////////////////////////////////////////////////////////////////
    Route::post('pan_kyc', [KycController::class, 'updatePanKyc']);
    Route::post('nominee_kyc', [KycController::class, 'updateNomineeKyc']);
    Route::post('get_aadharotp', [KycController::class, 'getAadharOtp']);
    Route::post('aadhar_kyc', [KycController::class, 'updateAadharKyc']);
    Route::post('get_kyc', [KycController::class, 'getKycStatus']);
    Route::post('get_kyc_data', [KycController::class, 'getKycData']);
    //////////////////////////////////////////////////////////////// kyc /////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////// PDF /////////////////////////////////////////////////////////////////
    Route::post('generate_pdf', [PDFController::class, 'generatePDF']);
    //////////////////////////////////////////////////////////////// PDF /////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////// support /////////////////////////////////////////////////////////////////
    Route::post('request_support', [SupportController::class, 'requestSupport']);
    Route::post('support_history', [SupportController::class, 'supportHistory']);
    //////////////////////////////////////////////////////////////// support /////////////////////////////////////////////////////////////////


    //////////////////////////////////withdraw //////////////////////////////////////////////////////////////////
    Route::post('withdraw', [WithdrawalController::class, 'WithdrawAmnt']);
    Route::post('withdraw_history', [WithdrawalController::class, 'withdrawHistory']);

    //////////////////////////////////withdraw //////////////////////////////////////////////////////////////////



    /////////////////////////////////bank Data ////////////////////////////////////////////
    Route::post('add_bank', [BankController::class, 'updateBankDetails']);
    Route::post('get_bank', [BankController::class, 'getBankData']);
    Route::post('get_kyc_details', [BankController::class, 'getNewBankData']);
    Route::post('delete_bank_details', [BankController::class, 'deleteBankDetails']);
    /////////////////////////////////bank Data ////////////////////////////////////////////



    ////////////////////////////////////////////////////razorpay payment add fund start ////////////////////////////
    Route::post('order_make', [PaymentController::class, 'createOrder']);
    Route::post('update_order', [PaymentController::class, 'updateOrder']);

    ////////////////////////////////////////////////////razorpay payment add fund end ////////////////////////////




    /////////////////Recharge APi Strat ///////////////////////////////////

        //////////////Recharge investment///////////////////////
    Route::get('get_recharge_package', [RechargeController::class, 'packages']);
    Route::post('buy_recharge_package', [RechargeController::class, 'buyPackage']);
    Route::post('get_recharge_royalty', [RechargeController::class, 'getRechargeRoyalty']);
    Route::post('get_recharge_tour', [RechargeController::class, 'getRechargeTour']);


         //////////////Recharge investment///////////////////////
    Route::post('operators', [RechargeController::class, 'fetchAndSaveOperators']);
    Route::post('get_operators', [RechargeController::class, 'getOperatorData']);
    // Route::get('get_circles', [RechargeController::class, 'getCircleData']);
     Route::post('recharge_request', [RechargeController::class, 'rechargeRequest']);
   Route::post('view_plan', [RechargeController::class, 'viewPlan']);
     Route::post('fetch_bill', [RechargeController::class, 'fetch_bill']);
    // Route::post('fetch_bill_info', [RechargeController::class, 'fetchfBillinfo']);
    // Route::post('fetch_bill_info_check', [RechargeController::class, 'fetchfBillinfoCheck']);
      Route::post('recharge_history', [RechargeController::class, 'rechargeHistory']);
    // Route::post('bbps_pay', [RechargeController::class, 'BBPSBillPay']);
    // Route::post('fastag', [RechargeController::class, 'fetchBillFastag']);
    // Route::post('fetch_request', [RechargeController::class, 'fetchBillRequest']);
    // Route::post('fetch_operator', [RechargeController::class, 'operatorFetch']);
    // Route::get('metro_recharge', [RechargeController::class, 'MetroRecharge']);



    ///////////////////new recharge api end ////////////////////////////////////////
        Route::post('set_providers', [RechargeController::class, 'providers']);
        Route::post('get_providers', [RechargeController::class, 'getProviders']);
        Route::post('recharge_services', [RechargeController::class, 'recharge_services']);
        Route::post('handle_recharge_services', [RechargeController::class, 'handleRechargecallback']);
        Route::post('validate_provider', [RechargeController::class, 'validateProvider']);
        Route::post('bill_verify', [RechargeController::class, 'biilVerify']);
        Route::post('bill_payment', [RechargeController::class, 'billPayment']);


    /////////////////////////////////new api Recharge route ambikamultiservices ///////////////////////////////////////////////////////////////


    /////////////////////////////////new api Recharge route ambikamultiservices ///////////////////////////////////////////////////////////////

    Route::post('recharge_req', [RechargeController::class, 'rechargeReq']);
    Route::post('get_operator', [RechargeController::class, 'getOperator']);
    Route::post('fetch_bills', [RechargeController::class, 'fetchFill']);
    Route::post('fetch_dth_info', [RechargeController::class, 'fetchDTHinfo']);


    /////////////////////////////// bus api ///////////////////////////////////////////////////////////////////////////////////////////////
     Route::post('get_source', [RechargeController::class, 'GetSourceList']);
     Route::post('get_destination', [RechargeController::class, 'GetDestinationList']);

    /////////////////////////////// bus api ///////////////////////////////////////////////////////////////////////////////////////////////



    /////////////////////////////// Hotel api ///////////////////////////////////////////////////////////////////////////////////////////////

    Route::get('hotel_availability', [RechargeController::class, 'hotelAvailability']);
    Route::get('city_search', [RechargeController::class, 'citySearch']);


    /////////////////////////////// Hotel api ///////////////////////////////////////////////////////////////////////////////////////////////




    /////////////////////////////////new api Recharge route ambikamultiservices ///////////////////////////////////////////////////////////////



     Route::post('add_fund_request', [FundRequestController::class, 'addFundRequest']);
     Route::post('fetch_fund_data', [FundRequestController::class, 'FetchFundData']);


    /////////////////////////////////new api game route  ///////////////////////////////////////////////////////////////



     Route::post('game', [GameController::class, 'index']);
     Route::post('play_game', [GameController::class, 'joinGame']);
     Route::post('get_participates', [GameController::class, 'getParticipateList']);
     Route::post('get_wins', [GameController::class, 'getWinsList']);
     Route::post('get_wins_new', [GameController::class, 'getWinsList2']);






    /////////////////////////////////new api game route  ///////////////////////////////////////////////////////////////





       /////////////////////////////////Loan Api ///////////////////////////////////////////////////////////////
       Route::get('get_loans_pkg', [LoanController::class, 'getPackage']);
       Route::get('get_loans_list', [LoanController::class, 'list']);
       Route::post('loan_investment', [LoanController::class, 'buyPackage']);
       Route::post('approve_loan', [LoanController::class, 'approveLoan']);
       Route::post('paid_Loan', [LoanController::class, 'paidLoan']);




       /////////////////////////////////Loan Api ///////////////////////////////////////////////////////////////



    /////////////////////////////////////E-Bike Apis start ////////////////////////////////////////////////////////////

    Route::get('get_ebike_pkg', [EbikeController::class, 'getPackage']);
    Route::post('ebike_invest', [EbikeController::class, 'buyPackage']);
    Route::post('get_royalty', [EbikeController::class, 'getRoyalty']);
    Route::post('bike_income_history', [EbikeController::class, 'EbikeCommissionHistory']);





    /////////////////////////////////////E-Bike Apis end ////////////////////////////////////////////////////////////

    /////////////////////////////////////Elite Apis start ////////////////////////////////////////////////////////////

    Route::get('get_elite_pkg', [EliteController::class, 'getPackage']);
    Route::post('elite_invest', [EliteController::class, 'buyPackage']);
    Route::post('left_rigth_data', [EliteController::class, 'leftRigthData']);
//     Route::post('get_royalty', [EbikeController::class, 'getRoyalty']);
//     Route::post('bike_income_history', [EbikeController::class, 'EbikeCommissionHistory']);





    /////////////////////////////////////Elite Apis end ////////////////////////////////////////////////////////////

    /////////////////////////////////////fly Apis start ////////////////////////////////////////////////////////////

    Route::get('get_fly_pkg', [FlyController::class, 'getPackage']);
    Route::post('fly_invest', [FlyController::class, 'buyPackage']);
    Route::post('fly_ticket', [FlyController::class, 'flyTicket']);
    Route::post('fly_decreypt', [FlyController::class, 'sendEncodedInvestIdToApp']);
//     Route::post('left_rigth_data', [FlyController::class, 'leftRigthData']);
//     Route::post('get_royalty', [EbikeController::class, 'getRoyalty']);
//     Route::post('bike_income_history', [EbikeController::class, 'EbikeCommissionHistory']);





    /////////////////////////////////////Elite Apis end ////////////////////////////////////////////////////////////

    /////////////////////////////////////Tour Apis start ////////////////////////////////////////////////////////////

    Route::get('get_tour_pkg', [TourController::class, 'getPackage']);
    Route::post('tour_invest', [TourController::class, 'buyPackage']);
//     Route::post('bike_income_history', [TourController::class, 'EbikeCommissionHistory']);





    /////////////////////////////////////Tour Apis end ////////////////////////////////////////////////////////////





    /////////////////Recharge APi End ///////////////////////////////////

    ////////////////////////////E-commerce //////////////////////////////////
    Route::post('get_product', [ProductController::class, 'getProduct']);
    Route::post('add_to_cart', [ProductController::class, 'addToCart']);
    Route::post('get_cart', [ProductController::class, 'getCartDetails']);
    Route::post('clear_cart', [ProductController::class, 'deleteCart']);
    Route::post('add_quantity', [ProductController::class, 'addQuantity']);
    Route::post('add_address', [ProductController::class, 'addShippingAddress']);
    Route::post('get_address', [ProductController::class, 'getAddress']);
    Route::post('take_order', [ProductController::class, 'placeOrder']);
    Route::post('order_history', [ProductController::class, 'orderHistory']);


    ////////////////////////////E-commerce //////////////////////////////////

    ////////////////////////////Doantion //////////////////////////////////
    Route::get('donation', [DonationController::class, 'index']);
    Route::post('donate', [DonationController::class, 'store']);
    ////////////////////////////Doantion //////////////////////////////////


    ///////////////////////////Buy Sell APi /////////////////////////////////

    Route::post('/buy_btc', [BuySellController::class, 'buyBtc']);
    Route::post('/get_price', [BuySellController::class, 'getPrice']);
    Route::post('/sell_btc', [BuySellController::class, 'sellBtc']);
    Route::post('/buy_gold', [BuySellController::class, 'buyGold']);
    Route::post('/sell_gold', [BuySellController::class, 'sellGold']);
    ///////////////////////////Buy Sell APi /////////////////////////////////


    //////////////////////////Lucky Draw ///////////////////////////////////
    Route::post('/fetch_lucky_draw', [LuckyDrawController::class, 'paidLuckyDraw']);
    Route::post('/join_lucky_draw', [LuckyDrawController::class, 'joinLuckyDraw']);
    Route::post('/fetch_gift_draw', [LuckyDrawController::class, 'giftLuckyDraw']);
    Route::post('/join_gift_draw', [LuckyDrawController::class, 'joinGiftDraw']);
    Route::post('/fetch_tour_draw', [LuckyDrawController::class, 'tourLuckyDraw']);
    Route::post('/join_tour_draw', [LuckyDrawController::class, 'joinTourDraw']);
    Route::post('/exists_spinner', [LuckyDrawController::class, 'ExistsSpinner']);
    Route::post('/join_spinner', [LuckyDrawController::class, 'joinSpinner']);
    Route::post('/getlucky_participate', [LuckyDrawController::class, 'getPaidLuckyParticipate']);
    Route::post('/getgift_participate', [LuckyDrawController::class, 'getGiftLuckyParticipate']);
    Route::post('/gettour_participate', [LuckyDrawController::class, 'getTourLuckyParticipate']);
    Route::post('/fetch_jackpot_draw', [LuckyDrawController::class, 'tourJackpotDraw']);
    Route::post('/join_jackpot_draw', [LuckyDrawController::class, 'joinJackpotDraw']);
    Route::post('/getjackpot_participate', [LuckyDrawController::class, 'getJackpotParticipate']);
    Route::post('/get_all_wins', [LuckyDrawController::class, 'getDrawWins']);
    Route::post('/get_jackpot_wins', [LuckyDrawController::class, 'getJackpotWins']);
    Route::post('/get_wins_spinner', [LuckyDrawController::class, 'getWinsSpinner']);

    //////////////////////////Lucky Draw ///////////////////////////////////

});
// Route::get('/payment-success', [FundRequestController::class, 'success'])->name('success');
// Route::get('/payment-success', function () {
//     return view('success');
// })->name('success');
Route::controller(AuthController::class)->group(function() {
    // Route::get('check', [InvestmentController::class, 'check']);
    Route::post('login','login');
    Route::post('register','register');
    Route::post('register_new','registerNew');
    Route::post('simple_register','simpleRegister');
    Route::post('validate_register_new','validateRegister');
    Route::post('otp_genrate','sendOtp');
    Route::post('validate_mobile','validateUser');
    Route::post('verify_account','verifyAccount');
    Route::post('verify_pan','verifyPan');
    Route::post('testing','test');
    Route::post('otp_genrate_w','sendOtpnew');
    Route::post('otp_genrate_what','sendOtpWhatsapp');
    Route::get('check_voice','checkVoice');
});

// Route::middleware('auth:sanctum')->post('/email/send-verification', [EmailVerificationController::class, 'sendVerificationEmail']);
// Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
// ->name('verification.verify');