# EasyDigiPays - Complete API & Route Documentation

> **Generated:** 26 Feb 2026
> **Domain:** easydigipays.com
> **Server:** EC2 (13.205.144.165) via Cloudflare

---

## Table of Contents

1. [Web Pages (Public)](#1-web-pages-public)
2. [Web Auth Routes (Laravel Breeze)](#2-web-auth-routes-laravel-breeze)
3. [Admin Panel (Filament)](#3-admin-panel-filament)
4. [Public API Routes (No Auth)](#4-public-api-routes-no-auth)
5. [Authenticated API Routes (Sanctum)](#5-authenticated-api-routes-sanctum)
6. [External Third-Party Integrations](#6-external-third-party-integrations)
7. [Hardcoded Credentials (TO FIX)](#7-hardcoded-credentials-to-fix)
8. [Security Issues (TO FIX)](#8-security-issues-to-fix)

---

## 1. Web Pages (Public)

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/` | `BaseController@index` | Homepage — handles referral redirect via `?ref=` param |
| GET | `/contact` | `BaseController@contact` | Contact page |
| GET | `/conditions` | `BaseController@conditions` | Terms & conditions page |
| GET | `/privacy` | `BaseController@privacy` | Privacy policy page |
| GET | `/return_policy` | `BaseController@policyReturn` | Return policy page |
| GET | `/refund_policy` | `BaseController@policyRefund` | Refund policy page |
| GET | `/payment/success` | `FundRequestController@success` | Payment success page |
| GET | `/swagger` | *(view: api-docs)* | Swagger API documentation |

**Theme:** `resources/views/layouts/mtheme1.blade.php` (main layout)

---

## 2. Web Auth Routes (Laravel Breeze)

### Guest Routes (unauthenticated users)

| Method | URL | Controller | Name |
|--------|-----|------------|------|
| GET | `/register` | `RegisteredUserController@create` | `register` |
| POST | `/register` | `RegisteredUserController@store` | — |
| GET | `/login` | `AuthenticatedSessionController@create` | `login` |
| POST | `/login` | `AuthenticatedSessionController@store` | — |
| GET | `/forgot-password` | `PasswordResetLinkController@create` | `password.request` |
| POST | `/forgot-password` | `PasswordResetLinkController@store` | `password.email` |
| GET | `/reset-password/{token}` | `NewPasswordController@create` | `password.reset` |
| POST | `/reset-password` | `NewPasswordController@store` | `password.store` |

### Auth Routes (logged-in users)

| Method | URL | Controller | Name |
|--------|-----|------------|------|
| GET | `/verify-email` | `EmailVerificationPromptController` | `verification.notice` |
| GET | `/verify-email/{id}/{hash}` | `VerifyEmailController` | `verification.verify` |
| POST | `/email/verification-notification` | `EmailVerificationNotificationController@store` | `verification.send` |
| GET | `/confirm-password` | `ConfirmablePasswordController@show` | `password.confirm` |
| POST | `/confirm-password` | `ConfirmablePasswordController@store` | — |
| PUT | `/password` | `PasswordController@update` | `password.update` |
| POST | `/logout` | `AuthenticatedSessionController@destroy` | `logout` |
| GET | `/profile` | `ProfileController@edit` | `profile.edit` |
| PATCH | `/profile` | `ProfileController@update` | `profile.update` |
| DELETE | `/profile` | `ProfileController@destroy` | `profile.destroy` |

---

## 3. Admin Panel (Filament)

**URL:** `/admin`
**Auth Guard:** `admin` (separate from user auth)
**Login:** `/admin/login`

### Admin Resources (35 total)

| Resource | Model | Admin URL |
|----------|-------|-----------|
| AdminResource | Admin | `/admin/admins` |
| AffiliateResource | Affiliate | `/admin/affiliates` |
| BankResource | Bank | `/admin/banks` |
| BannerResource | Banner | `/admin/banners` |
| CommiteeInvestmentResource | CommiteeInvestment | `/admin/commitee-investments` |
| DemoRewardResource | DemoReward | `/admin/demo-rewards` |
| DonateResource | Donate | `/admin/donates` |
| DonationResource | Donation | `/admin/donations` |
| FundRequestMethodResource | FundRequestMethod | `/admin/fund-request-methods` |
| GalleryResource | Gallery | `/admin/galleries` |
| HeadlineResource | Headline | `/admin/headlines` |
| InvestmentResource | Investment | `/admin/investments` |
| KycResource | Kyc | `/admin/kycs` |
| MeetingResource | Meeting | `/admin/meetings` |
| NewsResource | News | `/admin/news` |
| NotificationResource | Notification | `/admin/notifications` |
| OttCredentialResource | OttCredential | `/admin/ott-credentials` |
| PackageResource | Package | `/admin/packages` |
| PaymentMethodResource | PaymentMethod | `/admin/payment-methods` |
| PermissionResource | Permission | `/admin/permissions` |
| PlanDirectResource | PlanDirect | `/admin/plan-directs` |
| PlanLevelResource | PlanLevel | `/admin/plan-levels` |
| PlanRewardResource | PlanReward | `/admin/plan-rewards` |
| PlanRoiResource | PlanRoi | `/admin/plan-rois` |
| RechargeResource | Recharge | `/admin/recharges` |
| RoleResource | Role | `/admin/roles` |
| SavingFundInvestmentResource | SavingFundInvestment | `/admin/saving-fund-investments` |
| SettingResource | Setting | `/admin/settings` |
| SupportResource | Support | `/admin/supports` |
| TransactionResource | Transaction | `/admin/transactions` |
| UserFundRequestResource | UserFundRequest | `/admin/user-fund-requests` |
| UserResource | User | `/admin/users` |
| UserRewardResource | UserReward | `/admin/user-rewards` |
| WalletTypeResource | WalletType | `/admin/wallet-types` |
| WithdrawalResource | Withdrawal | `/admin/withdrawals` |

### Dashboard Widgets

- StatsOverview
- BusinessOverview
- WithdrawalStats
- UserChart
- InvestmentChart

---

## 4. Public API Routes (No Auth)

> **WARNING:** Several of these routes trigger financial operations without authentication.

### Authentication

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/login` | `AuthController@login` | User login (email/mobile + password) |
| POST | `/api/register` | `AuthController@register` | User registration with OTP validation |
| POST | `/api/register_new` | `AuthController@registerNew` | Full registration with bank + PAN |
| POST | `/api/simple_register` | `AuthController@simpleRegister` | Simplified registration |
| POST | `/api/validate_register_new` | `AuthController@validateRegister` | Validate registration inputs |
| POST | `/api/otp_genrate` | `AuthController@sendOtp` | Send SMS OTP |
| POST | `/api/otp_genrate_w` | `AuthController@sendOtpnew` | Send SMS + Email OTP |
| POST | `/api/otp_genrate_what` | `AuthController@sendOtpWhatsapp` | Send WhatsApp OTP |
| POST | `/api/validate_mobile` | `AuthController@validateUser` | Check if mobile exists |
| POST | `/api/verify_account` | `AuthController@verifyAccount` | Verify bank account via KYC API |
| POST | `/api/verify_pan` | `AuthController@verifyPan` | Verify PAN via KYC API |
| POST | `/api/testing` | `AuthController@test` | Test endpoint |
| GET | `/api/check_voice` | `AuthController@checkVoice` | Check voice call status |

### OTP & Password

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/generate_otp` | `OtpController@generateOtp` | Generate OTP |
| POST | `/api/forgot_password` | `PasswordController@generateNewPassword` | Reset password |
| POST | `/api/forgot_mpin` | `PasswordController@generateNewMpin` | Reset MPIN |

### Callbacks (Payment Webhooks)

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/callBack_scanPay` | `WithdrawalController@callBack_ScanPay` | ScanPay withdrawal callback |
| POST | `/api/callBack_Payout` | `WithdrawalController@callBack_Payout` | Payout callback |
| POST | `/api/callBack_click_Payout` | `WithdrawalController@callBack_Click_Payout` | ClicknCash payout callback |
| POST | `/api/callBack_New_Click_Payout` | `WithdrawalController@callBack_New_Click_Payout` | New ClicknCash callback |
| POST | `/api/callBack_payIn` | `FundRequestController@callBack_payIn` | ClicknCash pay-in callback |
| ANY | `/api/callback_fund` | `FundRequestController@callBackFundRequest` | EkQR payment callback |
| GET | `/api/callback_recharge` | `RechargeController@callback_recharge` | Recharge callback |
| POST | `/api/payment/handle_webhook` | `FundRequestController@handlewebhook` | Payment webhook handler |

### Public Data

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/fetch_referral` | `BaseController@fetchReferral` | Get referral by IP |
| GET | `/api/meeting` | `MeetingController@getMeeting` | Get meetings |
| GET | `/api/gallery` | `GalleryController@getGallery` | Get gallery items |
| POST | `/api/notification` | `GalleryController@notify` | Send notification |
| GET | `/api/get_app_version` | `AppController@getVersion` | Get app version |
| POST | `/api/test_gold` | `GoldController@test` | Test gold API |
| GET | `/api/call_recharge` | `RechargeController@call_recharge` | Call recharge API |
| GET | `/api/call_razorpay` | `PaymentController@verify` | Verify Razorpay payment |
| GET | `/api/make_handle_callback` | `FundRequestController@handle` | Handle PhonePe callback |

### UNPROTECTED Financial Routes (SECURITY RISK)

| Method | URL | Helper | Description | Risk |
|--------|-----|--------|-------------|------|
| GET | `/api/roiincmcls` | `RoiDistribute::roiClosing()` | ROI income closing | **HIGH** |
| GET | `/api/roifourincmcls` | `RoiDistribute::roiFourClosing()` | ROI 4x closing | **HIGH** |
| GET | `/api/dailyincmcls` | `Distribute::clearDailyInmcome()` | Clear daily income | **HIGH** |
| GET | `/api/royalty_distribution` | `Distribute::RoyaltyDistribution()` | Distribute royalty | **HIGH** |
| GET | `/api/royalty_recharge_distribution` | `Distribute::RoyaltyRechargeDistribution()` | Distribute recharge royalty | **HIGH** |
| GET | `/api/loan_pay` | `Distribute::loanDistribution()` | Process loan payments | **HIGH** |
| GET | `/api/is_Ebike_eligible` | `Distribute::IsEbikeEligible()` | Check ebike eligibility | MEDIUM |
| GET | `/api/is_recharge_trip_eligible` | `Distribute::IsRechargeTripEligible()` | Check recharge trip eligibility | MEDIUM |
| GET | `/api/fetch_gold_live` | `Distribute::fetchgold()` | Fetch live gold rates | LOW |
| GET | `/api/Jack_pot_distribution` | `Distribute::JackpotDistribution()` | Distribute jackpot prizes | **HIGH** |
| GET | `/api/Jack_pot_weekly_distribution` | `Distribute::JackpotWeeklyDistribution()` | Weekly jackpot distribution | **HIGH** |
| GET | `/api/anything_testing` | `Distribute::testAnyThing()` | Test method (UNKNOWN) | **HIGH** |
| GET | `/api/jackpot_users` | `Distribute::joinJackpotDrawUsers()` | Join jackpot draw | **HIGH** |
| GET | `/api/jackpot_users_weekly` | `Distribute::joinJackpotDrawUsersWeekly()` | Join weekly jackpot | **HIGH** |
| GET | `/api/ebike_daily_income` | `Distribute::ebikeDailyIncome()` | Ebike daily income | **HIGH** |
| GET | `/api/distrbute_ebike_binary_macth` | `Distribute::distrbuteEbikeBinaryMacth()` | Ebike binary matching | **HIGH** |
| GET | `/api/distrbute_ebike_binary_income` | `Distribute::distrbuteEbikeBinaryIncome()` | Ebike binary income | **HIGH** |
| GET | `/api/tour_daily_income` | `Distribute::tourDailyIncome()` | Tour daily income | **HIGH** |
| GET | `/api/distrbute_tour_binary_macth` | `Distribute::distrbuteTourBinaryMacth()` | Tour binary matching | **HIGH** |
| GET | `/api/elite_daily_income` | `Distribute::eliteDailyIncome()` | Elite daily income | **HIGH** |
| GET | `/api/elite_daily_new_income` | `Distribute::eliteDailynewIncome()` | Elite new daily income | **HIGH** |
| GET | `/api/distrbute_elite_binary_macth` | `Distribute::distrbuteEliteBinaryMacth()` | Elite binary matching | **HIGH** |
| GET | `/api/distrbute_elite_binary_income` | `Distribute::distrbuteEliteBinaryIncome()` | Elite binary income | **HIGH** |
| GET | `/api/clr_withdraw_request` | `Distribute::clrRequest()` | Clear withdrawal requests | **CRITICAL** |
| ANY | `/api/updateTimer` | `GameController@updateGameTimer` | Update game timer | MEDIUM |

---

## 5. Authenticated API Routes (Sanctum)

> Requires `Authorization: Bearer {token}` header

### Profile & User Management

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/update_profile` | `ProfileController@updateProfile` | Update user profile |
| GET | `/api/user` | `ProfileController@data` | Get user data |
| POST | `/api/validateUser` | `ProfileController@validateUser` | Validate user |
| POST | `/api/upload_image` | `ProfileController@updateImage` | Upload profile image |
| POST | `/api/verify_both_Otp` | `ProfileController@verifyBothOtp` | Verify SMS + Email OTP |
| POST | `/api/verify_pan_details` | `ProfileController@verifyPan` | Verify PAN details |
| POST | `/api/update_user_details` | `ProfileController@updateUser` | Update user details |

### Dashboard

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/dashboard` | `DashboardController@dashboard` | Full dashboard data (wallets, stats) |
| POST | `/api/get_active_packages` | `DashboardController@getActivePackages` | Active investment packages |
| POST | `/api/check_pin` | `DashboardController@checkPin` | Validate transaction PIN |
| POST | `/api/update_pin` | `DashboardController@updatePin` | Update transaction PIN |
| GET | `/api/test_dash` | `DashboardController@test` | Test SMS gateway |
| GET | `/api/get_sponsor_info` | `DashboardController@getSponsorInfo` | Sponsor/upline info |
| GET | `/api/get_videos` | `DashboardController@getVideos` | Gallery videos |

### Investments

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/topup` | `InvestmentController@topup_api` | Buy investment package |
| GET | `/api/packages` | `InvestmentController@packages` | List investment packages |
| POST | `/api/test` | `InvestmentController@check` | Test endpoint |
| POST | `/api/invest_history` | `InvestmentController@investHistory` | Investment history |
| POST | `/api/package_details` | `InvestmentController@getPackageDetails` | Package details |

### Committee Investment

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/commitee_packages` | `InvestmentController@commitee_packages` | Committee packages |
| POST | `/api/take_commitee` | `InvestmentController@takeCommitee` | Join committee |

### Saving Fund

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/saving_packages` | `InvestmentController@saving_packages` | Saving fund packages |
| POST | `/api/take_saving` | `InvestmentController@takeSavingFund` | Start saving fund |

### Gold Investment

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/gold_packages` | `GoldController@packages` | Gold packages |
| POST | `/api/gold_packages_new` | `GoldController@packagesNew` | New gold packages |
| POST | `/api/gold_topup` | `GoldController@topup_api` | Buy gold package |
| POST | `/api/get_gold_royalty` | `GoldController@getGoldRoyalty` | Gold royalty info |

### Transactions & Income

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/transactions` | `TransactionController@all_transactions` | All transactions |
| POST | `/api/income_history` | `TransactionController@incomeHistory` | Income history |
| POST | `/api/today_income` | `TransactionController@todayIncomeHistory` | Today's income |

### Fund Requests (Add Money)

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/fund_data` | `FundRequestController@fund_request_details` | Payment method details |
| GET | `/api/add_fund` | `FundRequestController@addFund` | Add fund (legacy) |
| POST | `/api/fund_request` | `FundRequestController@addFundRequest` | Create UPI fund request |
| POST | `/api/fund_request_gateway` | `FundRequestController@fund_request_gateway` | EkQR payment gateway |
| POST | `/api/user_cancelled` | `FundRequestController@userCancelled` | Cancel fund request |
| POST | `/api/approve_fund_request` | `FundRequestController@approveFundRequest` | Approve fund request |
| POST | `/api/fund_history` | `FundRequestController@fundHistory` | Fund request history |
| POST | `/api/fund_request_simple` | `FundRequestController@fund_request` | Simple fund request (UTR) |
| POST | `/api/make_request_order` | `FundRequestController@makeOrder` | Create IMB payment order |
| POST | `/api/add_fund_request` | `FundRequestController@addFundRequest` | Add fund request (duplicate) |
| POST | `/api/fetch_fund_data` | `FundRequestController@FetchFundData` | Fetch fund data by txn ID |

### Team / Network

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/team` | `TeamController@getGeneration` | Team generation tree |
| POST | `/api/direct` | `TeamController@directs` | Direct referrals |
| POST | `/api/team_history` | `TeamController@getNewGeneration` | Team history |
| POST | `/api/team_historywithfilter` | `TeamController@getNewGenerationWithFilter` | Filtered team history |

### Fund Transfer

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/fund_convert` | `FundController@fundConvert` | Convert between wallets |
| POST | `/api/fund_transfer` | `FundController@fundTransfer` | Transfer fund to user |

### Rewards & Incentives

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/get_reward` | `RewardController@getReward` | Get reward details |
| POST | `/api/test_get_reward` | `RewardController@TestgetReward` | Test reward |
| POST | `/api/get_Month_Incentive` | `RewardController@getMonthIncentive` | Monthly incentive |
| POST | `/api/get_loan` | `RewardController@getLoan` | Get loan details |

### Password

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/change_password` | `PasswordController@updatePassword` | Change password |

### KYC

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/pan_kyc` | `KycController@updatePanKyc` | Submit PAN KYC |
| POST | `/api/nominee_kyc` | `KycController@updateNomineeKyc` | Submit nominee KYC |
| POST | `/api/get_aadharotp` | `KycController@getAadharOtp` | Get Aadhaar OTP |
| POST | `/api/aadhar_kyc` | `KycController@updateAadharKyc` | Submit Aadhaar KYC |
| POST | `/api/get_kyc` | `KycController@getKycStatus` | Get KYC status |
| POST | `/api/get_kyc_data` | `KycController@getKycData` | Get KYC data |

### Bank

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/add_bank` | `BankController@updateBankDetails` | Add/update bank details |
| POST | `/api/get_bank` | `BankController@getBankData` | Get bank details |
| POST | `/api/get_kyc_details` | `BankController@getNewBankData` | Get KYC + bank details |
| POST | `/api/delete_bank_details` | `BankController@deleteBankDetails` | Delete bank details |

### Withdrawal

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/withdraw` | `WithdrawalController@WithdrawAmnt` | Create withdrawal request |
| POST | `/api/withdraw_history` | `WithdrawalController@withdrawHistory` | Withdrawal history |

### PDF

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/generate_pdf` | `PDFController@generatePDF` | Generate PDF document |

### Support

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/request_support` | `SupportController@requestSupport` | Submit support ticket |
| POST | `/api/support_history` | `SupportController@supportHistory` | Support history |

### Razorpay Payment

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/order_make` | `PaymentController@createOrder` | Create Razorpay order |
| POST | `/api/update_order` | `PaymentController@updateOrder` | Update Razorpay order |

### Recharge & Bills

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/get_recharge_package` | `RechargeController@packages` | Recharge packages |
| POST | `/api/buy_recharge_package` | `RechargeController@buyPackage` | Buy recharge package |
| POST | `/api/get_recharge_royalty` | `RechargeController@getRechargeRoyalty` | Recharge royalty |
| POST | `/api/get_recharge_tour` | `RechargeController@getRechargeTour` | Recharge tour info |
| POST | `/api/operators` | `RechargeController@fetchAndSaveOperators` | Fetch operators |
| POST | `/api/get_operators` | `RechargeController@getOperatorData` | Get operator data |
| POST | `/api/recharge_request` | `RechargeController@rechargeRequest` | Make recharge |
| POST | `/api/view_plan` | `RechargeController@viewPlan` | View recharge plans |
| POST | `/api/fetch_bill` | `RechargeController@fetch_bill` | Fetch bill details |
| POST | `/api/recharge_history` | `RechargeController@rechargeHistory` | Recharge history |
| POST | `/api/set_providers` | `RechargeController@providers` | Set providers |
| POST | `/api/get_providers` | `RechargeController@getProviders` | Get providers |
| POST | `/api/recharge_services` | `RechargeController@recharge_services` | Recharge services |
| POST | `/api/handle_recharge_services` | `RechargeController@handleRechargecallback` | Recharge callback |
| POST | `/api/validate_provider` | `RechargeController@validateProvider` | Validate provider |
| POST | `/api/bill_verify` | `RechargeController@biilVerify` | Bill verification |
| POST | `/api/bill_payment` | `RechargeController@billPayment` | Bill payment |
| POST | `/api/recharge_req` | `RechargeController@rechargeReq` | Recharge request (new) |
| POST | `/api/get_operator` | `RechargeController@getOperator` | Get operator (new) |
| POST | `/api/fetch_bills` | `RechargeController@fetchFill` | Fetch bills (new) |
| POST | `/api/fetch_dth_info` | `RechargeController@fetchDTHinfo` | Fetch DTH info |
| POST | `/api/get_source` | `RechargeController@GetSourceList` | Bus source list |
| POST | `/api/get_destination` | `RechargeController@GetDestinationList` | Bus destination list |
| GET | `/api/hotel_availability` | `RechargeController@hotelAvailability` | Hotel availability |
| GET | `/api/city_search` | `RechargeController@citySearch` | City search |

### Game

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/game` | `GameController@index` | Game info |
| POST | `/api/play_game` | `GameController@joinGame` | Join game |
| POST | `/api/get_participates` | `GameController@getParticipateList` | Game participants |
| POST | `/api/get_wins` | `GameController@getWinsList` | Game wins |
| POST | `/api/get_wins_new` | `GameController@getWinsList2` | Game wins (v2) |

### Loan

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/get_loans_pkg` | `LoanController@getPackage` | Loan packages |
| GET | `/api/get_loans_list` | `LoanController@list` | Loan list |
| POST | `/api/loan_investment` | `LoanController@buyPackage` | Apply for loan |
| POST | `/api/approve_loan` | `LoanController@approveLoan` | Approve loan |
| POST | `/api/paid_Loan` | `LoanController@paidLoan` | Mark loan paid |

### E-Bike

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/get_ebike_pkg` | `EbikeController@getPackage` | E-bike packages |
| POST | `/api/ebike_invest` | `EbikeController@buyPackage` | Buy e-bike package |
| POST | `/api/get_royalty` | `EbikeController@getRoyalty` | E-bike royalty |
| POST | `/api/bike_income_history` | `EbikeController@EbikeCommissionHistory` | E-bike income history |

### Elite

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/get_elite_pkg` | `EliteController@getPackage` | Elite packages |
| POST | `/api/elite_invest` | `EliteController@buyPackage` | Buy elite package |
| POST | `/api/left_rigth_data` | `EliteController@leftRigthData` | Binary tree data |

### Fly

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/get_fly_pkg` | `FlyController@getPackage` | Fly packages |
| POST | `/api/fly_invest` | `FlyController@buyPackage` | Buy fly package |
| POST | `/api/fly_ticket` | `FlyController@flyTicket` | Fly ticket |
| POST | `/api/fly_decreypt` | `FlyController@sendEncodedInvestIdToApp` | Decrypt fly invest |

### Tour

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/get_tour_pkg` | `TourController@getPackage` | Tour packages |
| POST | `/api/tour_invest` | `TourController@buyPackage` | Buy tour package |

### E-Commerce

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/get_product` | `ProductController@getProduct` | Get products |
| POST | `/api/add_to_cart` | `ProductController@addToCart` | Add to cart |
| POST | `/api/get_cart` | `ProductController@getCartDetails` | Get cart |
| POST | `/api/clear_cart` | `ProductController@deleteCart` | Clear cart |
| POST | `/api/add_quantity` | `ProductController@addQuantity` | Update quantity |
| POST | `/api/add_address` | `ProductController@addShippingAddress` | Add address |
| POST | `/api/get_address` | `ProductController@getAddress` | Get address |
| POST | `/api/take_order` | `ProductController@placeOrder` | Place order |
| POST | `/api/order_history` | `ProductController@orderHistory` | Order history |

### Donation

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/api/donation` | `DonationController@index` | Donation list |
| POST | `/api/donate` | `DonationController@store` | Make donation |

### Buy/Sell (Crypto & Gold)

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/buy_btc` | `BuySellController@buyBtc` | Buy Bitcoin |
| POST | `/api/get_price` | `BuySellController@getPrice` | Get crypto price |
| POST | `/api/sell_btc` | `BuySellController@sellBtc` | Sell Bitcoin |
| POST | `/api/buy_gold` | `BuySellController@buyGold` | Buy gold |
| POST | `/api/sell_gold` | `BuySellController@sellGold` | Sell gold |

### Lucky Draw

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| POST | `/api/fetch_lucky_draw` | `LuckyDrawController@paidLuckyDraw` | Paid lucky draw |
| POST | `/api/join_lucky_draw` | `LuckyDrawController@joinLuckyDraw` | Join lucky draw |
| POST | `/api/fetch_gift_draw` | `LuckyDrawController@giftLuckyDraw` | Gift lucky draw |
| POST | `/api/join_gift_draw` | `LuckyDrawController@joinGiftDraw` | Join gift draw |
| POST | `/api/fetch_tour_draw` | `LuckyDrawController@tourLuckyDraw` | Tour lucky draw |
| POST | `/api/join_tour_draw` | `LuckyDrawController@joinTourDraw` | Join tour draw |
| POST | `/api/exists_spinner` | `LuckyDrawController@ExistsSpinner` | Check spinner exists |
| POST | `/api/join_spinner` | `LuckyDrawController@joinSpinner` | Join spinner |
| POST | `/api/getlucky_participate` | `LuckyDrawController@getPaidLuckyParticipate` | Lucky draw participants |
| POST | `/api/getgift_participate` | `LuckyDrawController@getGiftLuckyParticipate` | Gift draw participants |
| POST | `/api/gettour_participate` | `LuckyDrawController@getTourLuckyParticipate` | Tour draw participants |
| POST | `/api/fetch_jackpot_draw` | `LuckyDrawController@tourJackpotDraw` | Jackpot draw |
| POST | `/api/join_jackpot_draw` | `LuckyDrawController@joinJackpotDraw` | Join jackpot |
| POST | `/api/getjackpot_participate` | `LuckyDrawController@getJackpotParticipate` | Jackpot participants |
| POST | `/api/get_all_wins` | `LuckyDrawController@getDrawWins` | All draw wins |
| POST | `/api/get_jackpot_wins` | `LuckyDrawController@getJackpotWins` | Jackpot wins |
| POST | `/api/get_wins_spinner` | `LuckyDrawController@getWinsSpinner` | Spinner wins |

---

## 6. External Third-Party Integrations

### Payment Gateways

| Service | URL | Used In | Purpose |
|---------|-----|---------|---------|
| **PhonePe** | `https://api.phonepe.com/apis/hermes/pg/v1/status/` | FundRequestController | Payment verification |
| **PhonePe Sandbox** | `https://api-preprod.phonepe.com/apis/pg-sandbox/` | FundRequestController | Sandbox payments |
| **EkQR** | `https://api.ekqr.in/api/create_order` | FundRequestController | UPI payment gateway |
| **EkQR** | `https://api.ekqr.in/api/check_order_status` | FundRequestController | Order status check |
| **IMB Pay** | `https://pay.imb.org.in/api/create-order` | FundRequestController | Payment orders |
| **ClicknCash** | `http://103.205.64.251:8080/clickncashapi/` | FundRequestController | UPI + payout |
| **Razorpay** | *(via SDK)* | PaymentController | Payment orders |

### KYC / Verification

| Service | URL | Used In | Purpose |
|---------|-----|---------|---------|
| **Cyrus Recharge KYC** | `https://cyrusrecharge.in/api/total-kyc.aspx` | AuthController, KycController, ProfileController | PAN + Aadhaar verification |

### SMS / OTP

| Service | URL | Used In | Purpose |
|---------|-----|---------|---------|
| **Click4BulkSMS (WhatsApp)** | `http://whatsapp.click4bulksms.in/wapp/api/send` | OtpController, AuthController, LoanController, etc. | WhatsApp OTP |
| **SMSMAA** | `https://smsmaa.com/SMS_API/sendsms.php` | AuthController, OtpController, DashboardController | SMS OTP |
| **Sarv Voice** | `https://obd37.sarv.com/api/voice/voice_broadcast.php` | AuthController | Voice call OTP |
| **AdworthSMS (Email)** | `http://email.adworthsms.com/pushemail.php` | AuthController, EmailVerificationController | Email OTP |

### Recharge / Bills

| Service | URL | Used In | Purpose |
|---------|-----|---------|---------|
| **KwikAPI** | `https://www.kwikapi.com/api/v2/` | RechargeController | Mobile recharge + bill pay |
| **Cyrus Recharge** | `https://cyrusrecharge.in/services_cyapi/payout_cyapi.aspx` | FundRequestController | Payout |

### Market Data

| Service | URL | Used In | Purpose |
|---------|-----|---------|---------|
| **Binance** | `https://api.binance.com/api/v3/ticker/price` | BuySellController, bscpurchase.js | Crypto prices |
| **RapidAPI Gold** | `https://gold-rates-india.p.rapidapi.com/api/gold-rates` | Distribute.php | Live gold rates |

### Crypto

| Service | URL | Used In | Purpose |
|---------|-----|---------|---------|
| **MLMDX Crypto** | `https://test.mlmdx.com/CryptoApi/public/api/get-address` | CryptoApiController | Crypto wallet address |

### CDN / Frontend

| Service | URL | Used In | Purpose |
|---------|-----|---------|---------|
| **Google Fonts** | `https://fonts.googleapis.com`, `https://fonts.bunny.net` | Blade templates | Web fonts |
| **Font Awesome CDN** | `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/` | Footer, Index | Icons |
| **Ethers.js** | `https://cdn.ethers.io/lib/ethers-5.2.umd.min.js` | mtheme1 layout | Web3 |
| **Web3Modal** | `https://unpkg.com/web3modal` | mtheme1 layout | Wallet connect |
| **Swagger UI** | `https://unpkg.com/swagger-ui-dist@5.11.0/` | api-docs view | API docs |
| **Play Store** | `https://play.google.com/store/apps/details?id=com.app.s2pay` | BaseController, header | App download |

---

## 7. Hardcoded Credentials (TO FIX)

> All of these MUST be moved to `.env` file

| Credential | Value | Files |
|------------|-------|-------|
| WhatsApp API Key | `71a9b0fbe3cb414583372e7c5664a5b4` | AuthController, OtpController, LoanController, GoldController, EbikeController, InvestmentController (9 places) |
| SMS Username | `welcomejoykrl` | AuthController, OtpController, DashboardController |
| SMS Password | `KRL999` | AuthController, OtpController, DashboardController |
| Email API Username | `shivakumar` | AuthController, EmailVerificationController |
| Email API Password | `88634ccf2gf40mqog` | AuthController, EmailVerificationController |
| EkQR Payment Key | `54122137-6fc4-44fe-b983-53e083e50a5a` | FundRequestController |
| KwikAPI Key | `89ef91-7f1fb4-929d67-514b34-14f28c` | RechargeController |
| Infura ID | `5f40cd78a0004e3dbe19bd078e6d520a` | bscpurchase.js |
| Fortmatic Key | `pk_test_391E26A3B43A3350` | bscpurchase.js |
| Crypto Wallet 1 | `0xE8557fe9F04bC76D58189af53bA4768063c62633` | bscpurchase.js |
| Crypto Wallet 2 | `0x06f4b96Ac79f9904a3E047b702c27e04ed0D7558` | bscpurchase.js |

---

## 8. Security Issues (TO FIX)

### CRITICAL: Unprotected Financial Routes

25+ routes that trigger income distribution, jackpot payouts, and withdrawal clearing are accessible to **anyone** without authentication. These should either:
- Be moved behind authentication middleware
- Be converted to artisan commands (run via cron)
- Be protected with a secret token/key

### HIGH: Wrong Domain References

| Current Domain | Should Be | File |
|----------------|-----------|------|
| `s2pay.life` | `easydigipays.com` | FundRequestController, BannerController, EmailVerificationController |
| `metvallypay.com` | `easydigipays.com` | GalleryController |
| `earnfarmx.com` | `easydigipays.com` | PaymentMethodResource |
| `test.mlmdx.com` | `easydigipays.com` | CryptoApiController |

### HIGH: Hardcoded Credentials

All API keys and passwords are hardcoded in PHP files instead of `.env`. See section 7.

### MEDIUM: Missing Controller Methods

These routes are defined but the methods don't exist in the controller:
- `callBack_ScanPay` — not found in WithdrawalController
- `callBack_Payout` — not found in WithdrawalController
- `callBack_Click_Payout` — not found in WithdrawalController
- `callBack_New_Click_Payout` — not found in WithdrawalController

### LOW: HTTP (not HTTPS) External Calls

These API calls use unencrypted HTTP:
- `http://whatsapp.click4bulksms.in/wapp/api/send` (WhatsApp OTP)
- `http://103.205.64.251:8080/clickncashapi/` (Payment gateway)
- `http://email.adworthsms.com/pushemail.php` (Email API)
- `http://103.255.103.28/api/voice/fetch_report.php` (Voice API)

### LOW: Play Store Links to Wrong App

Play Store links point to `com.app.s2pay` — should be updated to easydigipays app ID.
