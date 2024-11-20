<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\MemberSignUpController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TeamOnboardController;

use App\Http\Controllers\Initiator\{
    SignUpController,
    CompanyInfoController,
    BusinessCategoryController,
    DeclarationController,
    ReRegistrationController
};

use App\Http\Controllers\Admin\{
    AdminHomeController,
    AdminEventController,
    AdminProfileController,
    AdminStaffController,
    AccountVerificationController,
    AdminReportController
};

use App\Http\Controllers\Procurement\{
    ProcurementHomeController,
    ProQuoteController,
    ReviewListController,
    ProDraftController,
    ProCompletedBiddingController,
    ProTeamController,
    ProEventController,
    ProProfileController
};

use App\Http\Controllers\Sales\{
    SalesHomeController,
    SalesRepliedEnquiryController,
    SalesExpiredEnquiryController,
    SalesDraftController,
    SalesEventsController,
    SalesProfileController
};

use App\Http\Controllers\Auth\{
    ForgotPasswordController,
    ResetPasswordController
};

use App\Http\Controllers\Member\{
    MemberHomeController,
    MemberQuoteController,
    MemberDraftController,
    MemberReviewListController,
    MemberCompletedBiddingController,
    MemberEventController,
    MemberProfileController
};

use App\Http\Controllers\FcmController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryPurchaseController;
use App\Http\Controllers\InviteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::post('/store-fcm-token', [FcmController::class, 'storeToken'])->name('storeFcmToken');
Route::get('/send-push-notification', [FcmController::class, 'sendPushNotification'])->name('send-push-notification');

Route::get('/', [WebsiteController::class,'index']);
Route::get('/admin', [WebsiteController::class,'index']);

Route::get('/about-us', [WebsiteController::class,'aboutUs']);
Route::get('/community-guidelines', [WebsiteController::class,'communityGuidelines']);
Route::get('/faq', [WebsiteController::class,'faq']);
Route::get('/privacy-policy', [WebsiteController::class,'privacyPolicy']);
Route::get('/user-agreement', [WebsiteController::class,'userAgreement']);

Route::get('/sign-up', [SignUpController::class,'index'])->name('sign-up');
Route::post('/sign-up/store', [SignUpController::class,'storeAndVerify'])->name('sign-up.store');
Route::get('/sign-up/verify', [SignUpController::class,'verify'])->name('sign-up.verify');
Route::post('/sign-up/resend-otp', [SignUpController::class,'resendOtp'])->name('sign-up.resend-otp');
Route::post('/sign-up/verify-otp', [SignUpController::class,'verifyOtp'])->name('sign-up.verify-otp');

Route::get('/sign-up/company-info', [CompanyInfoController::class,'index'])->name('sign-up.company-info');
Route::post('/sign-up/company-info/store', [CompanyInfoController::class,'store'])->name('sign-up.company-info.store');
Route::post('/sign-up/company-info/supersede', [CompanyInfoController::class,'storeSupersede'])->name('sign-up.company-info.storeSupersede');
Route::get('/sign-up/supersede/company-exists', [CompanyInfoController::class,'companyExists'])->name('sign-up.supersede.companyExists');

Route::post('/sign-up/company-info/upload-document', [CompanyInfoController::class,'uploadDocument'])->name('sign-up.company-info.uploadDocument');
Route::post('/sign-up/company-info/delete-document', [CompanyInfoController::class,'deleteDocument'])->name('sign-up.company-info.deleteDocument');

Route::post('/sign-up/company-info/upload-logo', [CompanyInfoController::class,'uploadLogo'])->name('sign-up.company-info.uploadLogo');
Route::post('/sign-up/company-info/delete-logo', [CompanyInfoController::class,'deleteLogo'])->name('sign-up.company-info.deleteLogo');

Route::get('/sign-up/business-category',[BusinessCategoryController::class,'index'])->name('sign-up.business-category');
Route::post('/sign-up/business-category',[BusinessCategoryController::class,'search'])->name('sign-up.business-category.search');
Route::post('/sign-up/business-category/alpha',[BusinessCategoryController::class,'alpha'])->name('sign-up.business-category.alpha');
Route::post('/sign-up/business-category/subcategory', [BusinessCategoryController::class,'getSubcategories'])->name('sign-up.business-category.subcategory');
Route::post('/sign-up/business-category/selected', [BusinessCategoryController::class,'selectedSubcategories'])->name('sign-up.business-category.selected');
Route::delete('/sign-up/business-category/delete/{id}', [BusinessCategoryController::class,'deleteSelected'])->name('sign-up.business-category.delete');
Route::post('/sign-up/business-category/add', [BusinessCategoryController::class,'addActivity'])->name('sign-up.business-category.add');
 
Route::get('/sign-up/declaration', [DeclarationController::class,'index'])->name('sign-up.declaration');
Route::get('/sign-up/terms-and-conditions', [DeclarationController::class,'terms'])->name('sign-up.terms-and-conditions');
Route::get('/sign-up/download-declaration', [DeclarationController::class,'download'])->name('sign-up.declaration.download');
Route::post('/sign-up/declaration/upload', [DeclarationController::class,'upload'])->name('sign-up.declaration.upload');
Route::post('/sign-up/declaration/delete', [DeclarationController::class,'delete'])->name('sign-up.declaration.delete');

Route::get('/sign-up/registration-edit', [DeclarationController::class,'edit'])->name('sign-up.edit');
Route::post('/sign-up/registration-edit', [DeclarationController::class,'update'])->name('sign-up.update');

Route::get('onboarding/{token}',  [SignUpController::class,'onboarding'])->name('onboarding');
Route::get('activate/{token}',  [SignUpController::class,'activate'])->name('activate');
Route::post('registration',  [SignUpController::class,'setPassword'])->name('registration.setPassword');

Route::get('/sign-up/review-verification', [SignUpController::class,'review'])->name('sign-up.review-verification');

// Common
Route::post('get-document',  [SignUpController::class,'getDocumentByCountry'])->name('getDocumentByCountry');
Route::post('get-region',  [SignUpController::class,'getRegionByCountry'])->name('getRegionByCountry');


// Re Registration 
Route::get('registration/{token}',  [ReRegistrationController::class,'registrationProcess'])->name('registration');
Route::get('/register/verify', [ReRegistrationController::class,'verify'])->name('registration.reVerify');
Route::post('/register/verify-otp', [ReRegistrationController::class,'verifyOtp'])->name('registration.verify-otp');

/*****************************************  Administrator Start **************************************/ 

    Route::get('/admin/dashboard', [AdminHomeController::class,'index'])->name('admin.dashboard');
    Route::post('/admin/chart', [AdminHomeController::class,'chartData'])->name('admin.chart');
    
    Route::post('/admin/sales-report', [AdminReportController::class,'sales'])->name('admin.sales.report');
    Route::post('/admin/procurement-report', [AdminReportController::class,'procurement'])->name('admin.procurement.report');
    Route::post('/admin/sales-report-pdf', [AdminReportController::class,'salesPdf'])->name('admin.sales.reportpdf');
    Route::post('/admin/procurement-report-pdf', [AdminReportController::class,'procurementPdf'])->name('admin.procurement.reportpdf');


    Route::get('/admin/edit-admin', [AdminHomeController::class,'adminEdit'])->name('admin.adminEdit');
    Route::post('/admin/update-admin/{id}', [AdminHomeController::class,'adminUpdate'])->name('admin.adminUpdate');

    Route::get('/admin/create-procurement', [AdminHomeController::class,'procurementCreate'])->name('admin.procurementCreate');
    Route::get('/admin/create-sales', [AdminHomeController::class,'salesCreate'])->name('admin.salesCreate');
    Route::post('/admin/update-user', [AdminHomeController::class,'createUpdateUser'])->name('admin.createUpdateUser');
    Route::get('/admin/complete', [AdminHomeController::class,'complete'])->name('admin.complete');

    Route::get('/admin/supersede/account-verification', [AdminHomeController::class,'supersedeAccountVerification'])->name('admin.supersede.accountVerify');

    // Upcoming Events Starts
        Route::get('/admin/upcoming-events', [AdminEventController::class,'index'])->name('admin.upcomingEvents');
    // Upcoming Events Ends

    // Profile Starts
        Route::get('/admin/profile', [AdminProfileController::class,'index'])->name('admin.profile');
        Route::get('/admin/edit-profile', [AdminProfileController::class,'edit'])->name('admin.editProfile');
        
        Route::get('/admin/notifications', [AdminProfileController::class,'notification'])->name('admin.notification'); 
        Route::get('/admin/payment-verification/intro', [AccountVerificationController::class,'paymentVerificationInfo'])->name('admin.paymentVerification.info'); 
        Route::get('/admin/payment-verification/intro1', [AccountVerificationController::class,'paymentVerificationInfo1'])->name('admin.paymentVerification.info1'); 
        Route::get('/admin/payment-verification', [AccountVerificationController::class,'paymentVerification'])->name('admin.paymentVerification'); 
        Route::post('/admin/verification/payment', [AccountVerificationController::class,'paynow'])->name('admin.verification.paynow'); 
        Route::get('/admin/verification/payment-response/{id}', [AccountVerificationController::class,'paymentResponse'])->name('admin.verification.success'); 
        Route::get('/admin/verification/payment-response-webhook/{id}', [AccountVerificationController::class,'paymentResponseWebhook'])->name('admin.verification.success'); 

        Route::get('/admin/payment-verification/order-summary',[AccountVerificationController::class,'orderSummary'])->name('admin.verification.orderSummary');
        Route::post('/admin/payment-verification/make-payment',[AccountVerificationController::class,'makePayment'])->name('admin.verification.makePayment');
        Route::get('/admin/payment-verification/payment-response/{id}',[AccountVerificationController::class,'paymentResponse'])->name('admin.verification.success');
    // Profile Ends

    // Staff Starts
        Route::post('/admin/staff/enable-disable', [AdminStaffController::class,'enableDisable'])->name('admin.staff.enableDisable');
        Route::get('/admin/staff/edit/{id}', [AdminStaffController::class,'edit'])->name('admin.staff.edit');
        Route::get('/admin/staff/create-sales', [AdminStaffController::class,'createSales'])->name('admin.staff.addSales');
        Route::post('/admin/staff/create-sales', [AdminStaffController::class,'createSalesAccount'])->name('admin.staff.createSalesAccount');
        Route::put('/admin/staff/update/{id}', [AdminStaffController::class,'update'])->name('admin.staff.update');
        Route::post('/admin/staff/update-profile-pic',[AdminStaffController::class,'updateProfilePic']);
        
        Route::get('/admin/member/profile/{id}', [AdminStaffController::class,'memberProfile'])->name('admin.member.profile');
    // Staff Ends
    
        Route::get('/subscription',[SubscriptionController::class,'index'])->name('subscription');
        Route::get('/billing',[SubscriptionController::class,'history'])->name('billing');
        Route::get('/subscription/view-bill/{id}',[SubscriptionController::class,'viewBill'])->name('subscription.view-bill');
        Route::get('/subscription/download-bill/{id}',[SubscriptionController::class,'downloadBill'])->name('subscription.download-bill');
        Route::get('/subscription/download-subscription-history',[SubscriptionController::class,'subscriptionHistory'])->name('subscription.download-subscription-history');
        Route::get('/subscription/plans',[SubscriptionController::class,'plans'])->name('subscription.plans');
        Route::get('/subscription/billing-summary',[SubscriptionController::class,'billingSummary'])->name('subscription.billingSummary');
        Route::post('/subscription/make-payment',[SubscriptionController::class,'makePayment'])->name('subscription.make-payment');
        Route::get('/subscription/order/{id}',[SubscriptionController::class,'order'])->name('subscription.order');
        Route::get('/subscription/payment-webhook/{id}',[SubscriptionController::class,'payment'])->name('subscription.payment');
        
        Route::post('/subscription/cancel',[SubscriptionController::class,'cancelSubscription'])->name('subscription.cancel');
        
        Route::get('/profile',[ProfileController::class,'index'])->name('profile.index');
        Route::get('/profile/edit',[ProfileController::class,'edit'])->name('profile.edit');
        Route::post('/profile/update',[ProfileController::class,'update'])->name('profile.update');
        Route::post('/profile/update-profile-pic',[ProfileController::class,'updateProfilePic']);
        Route::get('/profile/change-password',[ProfileController::class,'changePassword'])->name('profile.changePassword');
        Route::post('/profile/update-password',[ProfileController::class,'updatePassword'])->name('profile.updatePassword');
        Route::post('/profile/update-document',[ProfileController::class,'updateDocument'])->name('profile.updateDocument');
        
        Route::get('/company-profile',[ProfileController::class,'companyProfile'])->name('companyProfile.index');
        Route::post('/company-profile/update-logo',[ProfileController::class,'updateCompanyProfilePic'])->name('companyProfile.update-logo');
        
        Route::get('/profile/card',[ProfileController::class,'card'])->name('profile.card');
        
        
        Route::get('/category-purchase',[CategoryPurchaseController::class,'index'])->name('category-purchase.index');
        Route::get('/category-purchase/cart',[CategoryPurchaseController::class,'cart'])->name('category-purchase.cart');
        Route::post('/category-purchase/add',[CategoryPurchaseController::class,'add'])->name('category-purchase.add');
        Route::delete('/category-purchase/delete/{id}',[CategoryPurchaseController::class,'remove'])->name('category-purchase.delete');
        Route::get('/category-purchase/order-summary',[CategoryPurchaseController::class,'orderSummary'])->name('category-purchase.orderSummary');
        Route::post('/category-purchase/make-payment',[CategoryPurchaseController::class,'makePayment'])->name('category-purchase.makePayment');
        Route::get('/category-purchase/payment-response/{id}',[CategoryPurchaseController::class,'paymentResponse'])->name('category-purchase.success');
        Route::get('/category-purchase/unsubscribe',[CategoryPurchaseController::class,'unsubsubscribeCategory'])->name('category-purchase.unsubsubscribeCategory');
        Route::post('/category-purchase/unsubscribe/save',[CategoryPurchaseController::class,'unsubsubscribeCategorySave'])->name('category-purchase.unsubsubscribeCategorySave');

/*****************************************  Administrator Ends **************************************/ 


    Route::get('/logout', [AdminHomeController::class,'logout'])->name('logout');

    Route::post('/login', [LoginController::class,'login'])->name('login');
    Route::get('/login', function(){
        return redirect('/');
    });

    Route::get('/forgot-password', [ForgotPasswordController::class,'showForgotPasswordForm'])->name('password.forgot');
    Route::post('/forgot-password', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class,'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class,'resetPassword'])->name('password.update');




/*****************************************  Procurement Start **************************************/ 

Route::group(['middleware' => 'check.role:2'],function() {

    // Generate Quote Request
        Route::get('/procurement/quote/select-category', [ProQuoteController::class,'selectCategory'])->name('procurement.quote.selectCategory');
        Route::get('/procurement/quote/change-category/{id}', [ProQuoteController::class,'changeCategory'])->name('procurement.quote.changeCategory');
        Route::post('/procurement/quote/save-category', [ProQuoteController::class,'saveCategory'])->name('procurement.quote.saveCategory');
        Route::post('/procurement/quote/update-category', [ProQuoteController::class,'updateCategory'])->name('procurement.quote.updateCategory');
        Route::get('/procurement/quote/compose/{id}', [ProQuoteController::class,'compose'])->name('procurement.quote.compose');
        Route::post('/procurement/quote/compose/upload-attachment', [ProQuoteController::class,'uploadAttachment'])->name('procurement.quote.uploadAttachment');
        Route::post('/procurement/quote/compose/fetch-attachment', [ProQuoteController::class,'getEnquiryAttachments'])->name('procurement.getEnquiryAttachments');
        Route::post('/procurement/quote/compose/attchments/delete', [ProQuoteController::class,'deleteAttachments'])->name('procurement.quote.deleteAttachments');
        Route::post('/procurement/quote/compose/save-as-draft', [ProQuoteController::class,'saveAsDraft'])->name('procurement.quote.saveAsDraft');
        Route::post('/procurement/quote/compose/generate-quote', [ProQuoteController::class,'generateQuote'])->name('procurement.quote.generateQuote');
        Route::post('/procurement/quote/compose/undo-generate-quote', [ProQuoteController::class,'undoGenerateQuote'])->name('procurement.quote.undoGenerateQuote');
        Route::post('/procurement/quote/compose/edit-accepted-till', [ProQuoteController::class,'editAcceptedDate'])->name('procurement.quote.editAcceptedDate');
        Route::post('/procurement/report', [ProcurementHomeController::class,'report'])->name('procurement.report');
        
    // Bid Inbox Starts
        Route::any('/procurement/dashboard/{ref?}/{reply_id?}', [ProcurementHomeController::class, 'index'])->name('procurement.dashboard');
        Route::any('/procurement/fetch-all-enquiries', [ProcurementHomeController::class,'fetchAllEnquiries'])->name('procurement.fetchAllEnquiries');
        Route::any('/procurement/fetch-enquiry', [ProcurementHomeController::class,'fetchEnquiry'])->name('procurement.fetchEnquiry');
        Route::any('/procurement/skip-faq', [ProcurementHomeController::class,'skipFaq'])->name('procurement.skipFaq');
        Route::any('/procurement/answer-faq', [ProcurementHomeController::class,'answerFaq'])->name('procurement.answerFaq');
        Route::post('/procurement/read-reply',[ProcurementHomeController::class,'readReply'])->name('procurement.readReply');
        Route::post('/procurement/shortlist',[ProcurementHomeController::class,'shortlist'])->name('procurement.shortlist');
        Route::post('/procurement/approve-interest',[ProcurementHomeController::class,'approveInterest'])->name('procurement.approveInterest');
        Route::post('/procurement/select',[ProcurementHomeController::class,'select'])->name('procurement.select');
        Route::post('/procurement/unselect',[ProcurementHomeController::class,'unselect'])->name('procurement.unselect');
        Route::post('/procurement/hold',[ProcurementHomeController::class,'hold'])->name('procurement.hold');
        Route::post('/procurement/ignore',[ProcurementHomeController::class,'ignore'])->name('procurement.ignore');
        Route::post('/procurement/share',[ProcurementHomeController::class,'share'])->name('procurement.share');
        Route::post('/procurement/proceed',[ProcurementHomeController::class,'proceed'])->name('procurement.proceed');
    // Bid Inbox Ends

    // Review List Start
        Route::get('/procurement/review-list/send/{ref?}', [ReviewListController::class,'send'])->name('procurement.reviewList.send');
        Route::post('/procurement/review-list/send/fetch-all-enquiries', [ReviewListController::class,'fetchAllSendEnquiries'])->name('procurement.reviewList.fetchAllSendEnquiries');
        Route::post('/procurement/review-list/received/fetch-all-enquiries', [ReviewListController::class,'fetchAllReceivedEnquiries'])->name('procurement.reviewList.fetchAllReceivedEnquiries');
        Route::any('/procurement/review-list/fetch-enquiry', [ReviewListController::class,'fetchEnquiry'])->name('procurement.reviewList.fetchEnquiry');
        Route::get('/procurement/review-list/received/{ref?}', [ReviewListController::class,'received'])->name('procurement.reviewList.received');
        Route::post('/procurement/review-list/mark-as-reviewed', [ReviewListController::class,'markAsReviewed'])->name('procurement.reviewList.markAsReviewed');
        Route::post('/procurement/review-list/mark-as-completed', [ReviewListController::class,'markAsCompleted'])->name('procurement.reviewList.markAsCompleted');
        Route::post('/procurement/review-list/recall-share', [ReviewListController::class,'recallShare'])->name('procurement.reviewList.recallShare');
        Route::post('/procurement/review-list/save-suggestion', [ReviewListController::class,'sendSuggestion'])->name('procurement.reviewList.sendSuggestion');
    // Review List End


    // Draft Starts
        Route::get('/procurement/draft/{id?}', [ProDraftController::class,'index'])->name('procurement.draft');
        Route::post('/procurement/open-draft', [ProDraftController::class,'openDraft'])->name('procurement.openDraft');
        Route::delete('/procurement/discard-draft/{id}', [ProDraftController::class,'discardDraft'])->name('procurement.discardDraft');
    // Draft Ends

    // Completed Bidding Starts
        Route::get('/procurement/completed-bidding/send{ref?}', [ProCompletedBiddingController::class,'send'])->name('procurement.completedBidding.send');
        Route::get('/procurement/completed-bidding/received/{ref?}', [ProCompletedBiddingController::class,'received'])->name('procurement.completedBidding.received');
        Route::post('/procurement/completed-bidding/mark-as-uncompleted', [ProCompletedBiddingController::class,'markAsUnCompleted'])->name('procurement.completedBidding.markAsUnCompleted');
        Route::post('/procurement/completed-bidding/send/fetch-all-enquiries', [ProCompletedBiddingController::class,'fetchAllSendEnquiries'])->name('procurement.completedBidding.fetchAllSendEnquiries');
        Route::post('/procurement/completed-bidding/received/fetch-all-enquiries', [ProCompletedBiddingController::class,'fetchAllReceivedEnquiries'])->name('procurement.completedBidding.fetchAllReceivedEnquiries');
        Route::any('/procurement/completed-bidding/fetch-enquiry', [ProCompletedBiddingController::class,'fetchEnquiry'])->name('procurement.completedBidding.fetchEnquiry');
    // Completed Bidding Ends

    // Team Settings & Approvals Start
        Route::get('/procurement/team-account/approval/{ref?}', [ProTeamController::class,'approval'])->name('procurement.teamAccount.approval');
        Route::get('/procurement/team-account/settings', [ProTeamController::class,'team'])->name('procurement.teamAccount.settings');
        Route::post('/procurement/team-account/save-member', [ProTeamController::class,'saveMember'])->name('procurement.teamAccount.saveMember');
        Route::post('/procurement/team-account/update-member', [ProTeamController::class,'updateMember'])->name('procurement.teamAccount.updateMember');
        Route::post('/procurement/team-account/approval/all', [ProTeamController::class,'fetchAllApprovalEnquiries'])->name('procurement.teamAccount.fetchAllApprovalEnquiries');
        Route::post('/procurement/team-account/approve-quote', [ProTeamController::class,'approveQuote'])->name('procurement.teamAccount.approveQuote');
        Route::get('/procurement/team-account/search', [ProTeamController::class,'fetchMembers'])->name('procurement.teamAccount.fetchMembers'); 
    // Team Settings & Approvals End

    // Upcoming Events Starts
        Route::get('/procurement/upcoming-events', [ProEventController::class,'index'])->name('procurement.upcomingEvents');
    // Upcoming Events Ends

    // Profile Starts
        Route::get('/procurement/profile', [ProProfileController::class,'index'])->name('procurement.profile');
        Route::get('/procurement/edit-profile', [ProProfileController::class,'edit'])->name('procurement.editProfile');
        Route::put('/procurement/update-profile/{id}', [ProProfileController::class,'update'])->name('procurement.updateProfile');
        Route::post('/procurement/update-profile-pic',[ProProfileController::class,'updateProfilePic']);
        
         Route::get('/procurement/notifications', [ProProfileController::class,'notification'])->name('procurement.notification'); 
         Route::get('/procurement/todos', [ProProfileController::class,'todo'])->name('procurement.todos'); 
         Route::delete('/todos/{id}', [ProProfileController::class, 'destroyTodo'])->name('todos.destroy');
        
         Route::get('/procurement/invite',  [InviteController::class,'index'])->name('invite');
         Route::post('/procurement/send-invite',  [InviteController::class,'sendInvite'])->name('sendInvite');
    // Profile Ends
    
   

});

/*****************************************  Procurement Ends **************************************/ 




/*****************************************  Sales Starts ******************************************/

Route::group(['middleware' => 'check.role:3'],function() {

    // Received List Starts
        Route::get('/sales/dashboard/{id?}', [SalesHomeController::class,'index'])->name('sales.dashboard');
        Route::any('/sales/fetch-all-enquiries', [SalesHomeController::class,'fetchAllEnquiries'])->name('sales.fetchAllEnquiries');
        Route::any('/sales/fetch-enquiry', [SalesHomeController::class,'fetchEnquiry'])->name('sales.fetchEnquiry');
        Route::any('/sales/save-question', [SalesHomeController::class,'saveQuestion'])->name('sales.saveQuestion');
        Route::post('/sales/send-bid', [SalesHomeController::class,'sendBid'])->name('sales.sendBid');
        Route::post('/sales/send-interest', [SalesHomeController::class,'sendInterest'])->name('sales.sendInterest');
        Route::post('/sales/discard', [SalesHomeController::class,'discard'])->name('sales.discard');
        Route::post('/sales/save-as-draft', [SalesHomeController::class,'saveDraft'])->name('sales.saveDraft');
        Route::post('/sales/bid/upload-attachment', [SalesHomeController::class,'uploadAttachment'])->name('sales.bid.uploadAttachment');
        Route::post('/sales/bid/fetch-attachment', [SalesHomeController::class,'getEnquiryAttachments'])->name('sales.getEnquiryAttachments');
        Route::post('/sales/bid/attchments/delete', [SalesHomeController::class,'deleteAttachments'])->name('sales.bid.deleteAttachments');
        Route::post('/sales/report', [SalesHomeController::class,'report'])->name('sales.report');
    // Received List Ends

    //Replied Enquiries Starts
        Route::get('/sales/replied-enquiry/{id?}', [SalesRepliedEnquiryController::class,'index'])->name('sales.repliedEnquiry');
        Route::post('/sales/replied/fetch-all-enquiries', [SalesRepliedEnquiryController::class,'fetchAllEnquiries'])->name('sales.replied.fetchAllEnquiries');
        Route::post('/sales/replied/fetch-enquiry', [SalesRepliedEnquiryController::class,'fetchEnquiry'])->name('sales.replied.fetchEnquiry');
    //Replied Enquiries Ends

    //Expired Enquiries Starts
        Route::get('/sales/expired-enquiry/{id?}', [SalesExpiredEnquiryController::class,'index'])->name('sales.expiredEnquiry');
        Route::post('/sales/expired/fetch-all-enquiries', [SalesExpiredEnquiryController::class,'fetchAllEnquiries'])->name('sales.expired.fetchAllEnquiries');
        Route::post('/sales/expired/fetch-enquiry', [SalesExpiredEnquiryController::class,'fetchEnquiry'])->name('sales.expired.fetchEnquiry');
    //Expired Enquiries Ends

    //Draft Starts
        Route::get('/sales/draft/{id?}', [SalesDraftController::class,'index'])->name('sales.draft');
        Route::any('/sales/draft/fetch-all-enquiries', [SalesDraftController::class,'fetchAllEnquiries'])->name('sales.draft.fetchAllEnquiries');
    //Draft Ends

    //Upcoming Events Starts
        Route::get('/sales/upcoming-events', [SalesEventsController::class,'index'])->name('sales.events');
    //Upcoming Events Ends

    // Profile Starts
        Route::get('/sales/profile', [SalesProfileController::class,'index'])->name('sales.profile');
        Route::get('/sales/edit-profile', [SalesProfileController::class,'edit'])->name('sales.editProfile');
        Route::put('/sales/update-profile/{id}', [SalesProfileController::class,'update'])->name('sales.updateProfile');
        Route::post('/sales/update-profile-pic',[SalesProfileController::class,'updateProfilePic']);
        
        Route::get('/sales/notifications', [SalesProfileController::class,'notification'])->name('sales.notification'); 
    // Profile Ends

});

/*****************************************  Sales Ends ******************************************/

/************************************** Member Starts **************************************/

Route::group(['middleware' => 'check.role:4'],function() {

    // Bid Inbox Starts
        Route::get('/member/dashboard/{ref?}', [MemberHomeController::class,'index'])->name('member.dashboard');
        Route::any('/member/fetch-all-enquiries', [MemberHomeController::class,'fetchAllEnquiries'])->name('member.fetchAllEnquiries');
        Route::any('/member/fetch-enquiry', [MemberHomeController::class,'fetchEnquiry'])->name('member.fetchEnquiry');
        Route::any('/member/skip-faq', [MemberHomeController::class,'skipFaq'])->name('member.skipFaq');
        Route::any('/member/answer-faq', [MemberHomeController::class,'answerFaq'])->name('member.answerFaq');
        Route::post('/member/read-reply',[MemberHomeController::class,'readReply'])->name('member.readReply');
        Route::post('/member/shortlist',[MemberHomeController::class,'shortlist'])->name('member.shortlist');
        Route::post('/member/approve-interest',[MemberHomeController::class,'approveInterest'])->name('member.approveInterest');
        Route::post('/member/select',[MemberHomeController::class,'select'])->name('member.select');
        Route::post('/member/ignore',[MemberHomeController::class,'ignore'])->name('member.ignore');
        Route::post('/member/unselect',[MemberHomeController::class,'unselect'])->name('member.unselect');
        Route::post('/member/hold',[MemberHomeController::class,'hold'])->name('member.hold');
        Route::post('/member/share',[MemberHomeController::class,'share'])->name('member.share');
        Route::post('/member/report', [MemberHomeController::class,'report'])->name('member.report');
    // Bid Inboc Ends

    // Generate Quote Request Starts
        Route::get('/member/quote/select-category', [MemberQuoteController::class,'selectCategory'])->name('member.quote.selectCategory');
        Route::get('/member/quote/change-category/{id}', [MemberQuoteController::class,'changeCategory'])->name('member.quote.changeCategory');
        Route::post('/member/quote/save-category', [MemberQuoteController::class,'saveCategory'])->name('member.quote.saveCategory');
        Route::post('/member/quote/update-category', [MemberQuoteController::class,'updateCategory'])->name('member.quote.updateCategory');
        Route::get('/member/quote/compose/{id}', [MemberQuoteController::class,'compose'])->name('member.quote.compose');
        Route::post('/member/quote/compose/upload-attachment', [MemberQuoteController::class,'uploadAttachment'])->name('member.quote.uploadAttachment');
        Route::post('/member/quote/compose/fetch-attachment', [MemberQuoteController::class,'getEnquiryAttachments'])->name('member.getEnquiryAttachments');
        Route::post('/member/quote/compose/attchments/delete', [MemberQuoteController::class,'deleteAttachments'])->name('member.quote.deleteAttachments');
        Route::post('/member/quote/compose/save-as-draft', [MemberQuoteController::class,'saveAsDraft'])->name('member.quote.saveAsDraft');
        Route::post('/member/quote/compose/generate-quote', [MemberQuoteController::class,'generateQuote'])->name('member.quote.generateQuote');
         Route::post('/member/quote/compose/undo-generate-quote', [MemberQuoteController::class,'undoGenerateQuote'])->name('member.quote.undoGenerateQuote');
        Route::post('/member/quote/compose/edit-accepted-till', [MemberQuoteController::class,'editAcceptedDate'])->name('member.quote.editAcceptedDate');
    // Generate Quote Request Ends

    // Draft Starts
        Route::get('/member/draft/{id?}', [MemberDraftController::class,'index'])->name('member.draft');
        Route::post('/member/open-draft', [MemberDraftController::class,'openDraft'])->name('member.openDraft');
        Route::post('/member/discard-draft', [MemberDraftController::class,'discardDraft'])->name('member.discardDraft');
    // Draft Ends

    // Review List Start 
        Route::get('/member/review-list/send/{ref?}', [MemberReviewListController::class,'send'])->name('member.reviewList.send');
        Route::post('/member/review-list/send/fetch-all-enquiries', [MemberReviewListController::class,'fetchAllSendEnquiries'])->name('member.reviewList.fetchAllSendEnquiries');
        Route::post('/member/review-list/received/fetch-all-enquiries', [MemberReviewListController::class,'fetchAllReceivedEnquiries'])->name('member.reviewList.fetchAllReceivedEnquiries');
        Route::any('/member/review-list/fetch-enquiry', [MemberReviewListController::class,'fetchEnquiry'])->name('member.reviewList.fetchEnquiry');
        Route::get('/member/review-list/received/{ref?}', [MemberReviewListController::class,'received'])->name('member.reviewList.received');
        Route::post('/member/review-list/mark-as-reviewed', [MemberReviewListController::class,'markAsReviewed'])->name('member.reviewList.markAsReviewed');
        Route::post('/member/review-list/recall-share', [MemberReviewListController::class,'recallShare'])->name('member.reviewList.recallShare');
        Route::post('/member/review-list/save-suggestion', [MemberReviewListController::class,'sendSuggestion'])->name('member.reviewList.sendSuggestion');
    // Review List Ends

    // Completed Bidding Starts
        Route::get('/member/completed-bidding/send/{ref?}', [MemberCompletedBiddingController::class,'send'])->name('member.completedBidding.send');
        Route::get('/member/completed-bidding/received{ref?}', [MemberCompletedBiddingController::class,'received'])->name('member.completedBidding.received');
        Route::post('/member/completed-bidding/mark-as-uncompleted', [MemberCompletedBiddingController::class,'markAsUnCompleted'])->name('member.completedBidding.markAsUnCompleted');
        Route::post('/member/completed-bidding/send/fetch-all-enquiries', [MemberCompletedBiddingController::class,'fetchAllSendEnquiries'])->name('member.completedBidding.fetchAllSendEnquiries');
        Route::post('/member/completed-bidding/received/fetch-all-enquiries', [MemberCompletedBiddingController::class,'fetchAllReceivedEnquiries'])->name('member.completedBidding.fetchAllReceivedEnquiries');
        Route::any('/member/completed-bidding/fetch-enquiry', [MemberCompletedBiddingController::class,'fetchEnquiry'])->name('member.completedBidding.fetchEnquiry');
    // Completed Bidding Ends

    // Upcoming Events Starts
        Route::get('/member/upcoming-events', [MemberEventController::class,'index'])->name('member.upcomingEvents');
    // Upcoming Events Ends

    // Profile Starts
        Route::get('/member/profile', [MemberProfileController::class,'index'])->name('member.profile');
        Route::get('/member/edit-profile', [MemberProfileController::class,'edit'])->name('member.editProfile');
        Route::put('/member/update-profile/{id}', [MemberProfileController::class,'update'])->name('member.updateProfile');
        Route::post('/member/update-profile-pic',[MemberProfileController::class,'updateProfilePic']);
        
        Route::get('/member/notifications', [MemberProfileController::class,'notification'])->name('member.notification'); 
        Route::get('/member/todos', [MemberProfileController::class,'todo'])->name('member.todos'); 
    // Profile Ends

});

/************************************** Member Ends **************************************/


// Member Self Signup
    Route::get('/member/sign-up', [MemberSignUpController::class,'index'])->name('member.signUp');
    Route::post('/member/sign-up/check', [MemberSignUpController::class,'checkSignup'])->name('member.signUpCheck');
    Route::get('/member/sign-up/info', [MemberSignUpController::class,'info'])->name('member.signUpInfo');
    Route::post('/member/sign-up/checkinfo', [MemberSignUpController::class,'checkInfo'])->name('member.checkInfo');
    Route::get('/member/sign-up/veriy', [MemberSignUpController::class,'verify'])->name('member.verify');
    Route::post('/member/sign-up/resend-otp', [MemberSignUpController::class,'resendOtp'])->name('member.resendOtp');
    Route::post('/member/sign-up/check-otp', [MemberSignUpController::class,'checkOtp'])->name('member.checkOtp');
    Route::get('/member/sign-up/declaration', [MemberSignUpController::class,'declaration'])->name('member.declaration');
    Route::get('/member/sign-up/create-profile', [MemberSignUpController::class,'createProfile'])->name('member.createProfile');
    Route::post('/member/sign-up/collect-profile', [MemberSignUpController::class,'collectProfile'])->name('member.collectProfile');
    Route::get('/member/sign-up/activate', [MemberSignUpController::class,'activate'])->name('member.activate');
    Route::post('/member/sign-up/set-password', [MemberSignUpController::class,'setPassword'])->name('member.setPassword');
    
    Route::get('team/signup/{token}',  [TeamOnboardController::class,'checkTeamInfo'])->name('member.checktoken');
    Route::post('/team/sign-up/resend-otp', [TeamOnboardController::class,'resendOtp'])->name('team.onboard.resendOtp');
    Route::post('/team/sign-up/check-otps', [TeamOnboardController::class,'checkOtp'])->name('team.onboard.checkOtp');
    Route::post('/team/sign-up/check-otp', [TeamOnboardController::class,'checkOtp'])->name('team.onboard.verify');
    Route::get('/team/sign-up/declaration', [TeamOnboardController::class,'declaration'])->name('team.onboard.declaration');    
    Route::get('/team/sign-up/create-profile', [TeamOnboardController::class,'createProfile'])->name('team.onboard.createProfile');
    Route::post('/team/sign-up/collect-profile', [TeamOnboardController::class,'collectProfile'])->name('team.onboard.collectProfile');
    Route::get('/team/sign-up/activate', [TeamOnboardController::class,'activate'])->name('team.onboard.activate');
    Route::post('/team/sign-up/set-password', [TeamOnboardController::class,'setPassword'])->name('team.onboard.setPassword');
    