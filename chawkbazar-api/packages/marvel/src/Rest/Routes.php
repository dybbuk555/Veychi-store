<?php

use Illuminate\Support\Facades\Route;

use Marvel\Http\Controllers\AddressController;
use Marvel\Http\Controllers\ApprovalTokenController;
use Marvel\Http\Controllers\AttributeController;
use Marvel\Http\Controllers\AttributeValueController;
use Marvel\Http\Controllers\CompanyController;
use Marvel\Http\Controllers\CountryController;
use Marvel\Http\Controllers\CustomerReviewsController;
use Marvel\Http\Controllers\DisputeController;
use Marvel\Http\Controllers\DNIDocumentController;
use Marvel\Http\Controllers\LegalRepresentativeController;
use Marvel\Http\Controllers\MarketingController;
use Marvel\Http\Controllers\PremiumPlansController;
use Marvel\Http\Controllers\PremiumSubscriptionsController;
use Marvel\Http\Controllers\PrivacyPolicyController;
use Marvel\Http\Controllers\ProductController;
use Marvel\Http\Controllers\SalariesController;
use Marvel\Http\Controllers\SettingsController;
use Marvel\Http\Controllers\TicketCommentsController;
use Marvel\Http\Controllers\TicketController;
use Marvel\Http\Controllers\UserController;
use Marvel\Http\Controllers\TypeController;
use Marvel\Http\Controllers\OrderController;
use Marvel\Http\Controllers\OrderStatusController;
use Marvel\Http\Controllers\CategoryController;
use Marvel\Http\Controllers\CouponController;
use Marvel\Http\Controllers\AttachmentController;
use Marvel\Http\Controllers\ShippingController;
use Marvel\Http\Controllers\TaxController;
use Marvel\Enums\Permission;
use Marvel\Http\Controllers\ShopController;
use Marvel\Http\Controllers\TagController;
use Marvel\Http\Controllers\WithdrawController;

Route::post('/register', 'Marvel\Http\Controllers\UserController@register');
Route::post('/token', 'Marvel\Http\Controllers\UserController@token');
Route::post('/logout', 'Marvel\Http\Controllers\UserController@logout');
Route::post('/forget-password', 'Marvel\Http\Controllers\UserController@forgetPassword');
Route::post('/verify-forget-password-token', 'Marvel\Http\Controllers\UserController@verifyForgetPasswordToken');
Route::post('/reset-password', 'Marvel\Http\Controllers\UserController@resetPassword');
Route::post('/contact-us', 'Marvel\Http\Controllers\UserController@contactAdmin');
Route::post('/social-login-token', 'Marvel\Http\Controllers\UserController@socialLogin');
Route::post('/send-otp-code', 'Marvel\Http\Controllers\UserController@sendOtpCode');
Route::post('/verify-otp-code', 'Marvel\Http\Controllers\UserController@verifyOtpCode');
Route::post('/otp-login', 'Marvel\Http\Controllers\UserController@otpLogin');
Route::get('marketing-images-data', 'Marvel\Http\Controllers\MarketingController@index');
Route::apiResource('products', ProductController::class, [
    'only' => ['index', 'show']
]);
Route::apiResource('types', TypeController::class, [
    'only' => ['index', 'show']
]);
Route::apiResource('attachments', AttachmentController::class, [
    'only' => ['index', 'show']
]);
Route::apiResource('categories', CategoryController::class, [
    'only' => ['index', 'show']
]);
Route::apiResource('tags', TagController::class, [
    'only' => ['index', 'show']
]);



Route::get('featured-categories', 'Marvel\Http\Controllers\CategoryController@fetchFeaturedCategories');


// Route::get('fetch-parent-category', 'Marvel\Http\Controllers\CategoryController@fetchOnlyParent');
// Route::get('fetch-category-recursively', 'Marvel\Http\Controllers\CategoryController@fetchCategoryRecursively');

Route::apiResource('coupons', CouponController::class, [
    'only' => ['index', 'show']
]);

Route::post('coupons/verify', 'Marvel\Http\Controllers\CouponController@verify');


Route::apiResource('order-status', OrderStatusController::class, [
    'only' => ['index', 'show']
]);


Route::apiResource('attributes', AttributeController::class, [
    'only' => ['index', 'show']
]);

Route::apiResource('shops', ShopController::class, [
    'only' => ['index', 'show']
]);

Route::apiResource('attribute-values', AttributeValueController::class, [
    'only' => ['index', 'show']
]);

Route::apiResource('settings', SettingsController::class, [
    'only' => ['index']
]);


Route::group(['middleware' => ['can:' . Permission::CUSTOMER, 'auth:sanctum']], function () {
    Route::post('customer-dispute/message','Marvel\Http\Controllers\DisputeController@storeMessage');
    Route::delete('customer-dispute/message','Marvel\Http\Controllers\DisputeController@deleteMessage');
    Route::post('dispute/{id}','Marvel\Http\Controllers\DisputeController@store');
    Route::apiResource('customer-dispute',DisputeController::class,[
        'only'=>['index','show','update','destroy']
    ]);
    Route::post('products/review','Marvel\Http\Controllers\ProductController@addReview');
    Route::apiResource('orders', OrderController::class, [
        'only' => ['index', 'show', 'store']
    ]);

    Route::get('orders/tracking-number/{tracking_number}', 'Marvel\Http\Controllers\OrderController@findByTrackingNumber');
    Route::apiResource('customer-attachments', AttachmentController::class, [
        'only' => ['store', 'update', 'destroy']
    ]);
    Route::resource('issue-tickets',TicketController::class,[
        'only'=>['index', 'show', 'update', 'destroy','store']
    ]);
    Route::resource('issue-ticket-comment',TicketCommentsController::class,[
        'only'=>['index', 'show', 'update', 'delete','store']
    ]);

    Route::post('orders/checkout/verify', 'Marvel\Http\Controllers\CheckoutController@verify');
    Route::get('me', 'Marvel\Http\Controllers\UserController@me');
    Route::put('users/{id}', 'Marvel\Http\Controllers\UserController@update');
    Route::post('/change-password', 'Marvel\Http\Controllers\UserController@changePassword');
    Route::post('/update-contact', 'Marvel\Http\Controllers\UserController@updateContact');
    Route::apiResource('address', AddressController::class, [
        'only' => ['destroy']
    ]);
});

Route::get('popular-products', 'Marvel\Http\Controllers\AnalyticsController@popularProducts');

Route::group(
    ['middleware' => ['permission:' . Permission::STAFF . '|' . Permission::STORE_OWNER, 'auth:sanctum']],
    function () {
        Route::get('orders/export/all/{id?}',[OrderController::class,'allOrdersInStore']);
        Route::post('dispute-admin/message','Marvel\Http\Controllers\DisputeController@storeMessage');
        Route::delete('dispute-admin/message','Marvel\Http\Controllers\DisputeController@deleteMessage');
        Route::apiResource('dispute',DisputeController::class,[
            'only'=>['index','show','update','store','destroy']
        ]);


        Route::apiResource('premium-owner',PremiumSubscriptionsController::class,[
            'only'=>['index','show','update','destroy']
        ]);

        Route::apiResource('orders',OrderController::class,[
            'only'=>[
                'update',
                'destroy'
            ]
        ]);
        Route::apiResource('dispute',DisputeController::class,[
            'only'=>['index','show','update','store','destroy']
        ]);
        Route::resource('tickets',TicketController::class,[
            'only'=>['index', 'show', 'update', 'destroy','store']
        ]);
        Route::resource('ticket-comment',TicketCommentsController::class,[
            'only'=>['index', 'show', 'update', 'destroy','store']
        ]);
        Route::post('users/premium/purchase','Marvel\Http\Controllers\UserController@premiumPaymentIntent');
        Route::post('users/premium/make-premium','Marvel\Http\Controllers\UserController@makePremium');
        Route::put('orders',[OrderController::class,'update']);
        Route::patch('orders',[OrderController::class,'update']);
        Route::delete('orders',[OrderController::class,'destroy']);
        Route::get('analytics', 'Marvel\Http\Controllers\AnalyticsController@analytics');
        Route::apiResource('products', ProductController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('attributes', AttributeController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('attribute-values', AttributeValueController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);


    }
);

Route::post('import-products', 'Marvel\Http\Controllers\ProductController@importProducts');
Route::post('import-variation-options', 'Marvel\Http\Controllers\ProductController@importVariationOptions');
Route::get('export-products/{shop_id}', 'Marvel\Http\Controllers\ProductController@exportProducts');
Route::get('export-variation-options/{shop_id}', 'Marvel\Http\Controllers\ProductController@exportVariableOptions');
Route::post('import-attributes', 'Marvel\Http\Controllers\AttributeController@importAttributes');
Route::get('export-attributes/{shop_id}', 'Marvel\Http\Controllers\AttributeController@exportAttributes');
Route::apiResource('dni-document', DNIDocumentController::class, [
    'only' => ['store']
]);
Route::group(
    ['middleware' => ['permission:' . Permission::STORE_OWNER, 'auth:sanctum']],
    function () {
        Route::apiResource('shops', ShopController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('attachments', AttachmentController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('withdraws', WithdrawController::class, [
            'only' => ['store', 'index', 'show']
        ]);
        Route::apiResource('dni-document', DNIDocumentController::class, [
            'only' => ['index','show', 'update', 'destroy']
        ]);
        Route::apiResource('legal-representative', LegalRepresentativeController::class, [
            'only' => ['index','show','store', 'update', 'destroy']
        ]);
        Route::apiResource('company', CompanyController::class, [
            'only' => ['index','show','store', 'update', 'destroy']
        ]);

        Route::get('owner-info/{id}','Marvel\Http\Controllers\UserController@storeOwnerInfo');

        Route::apiResource('countries', CountryController::class, [
            'only' => ['index','show']
        ]);
        Route::get('shop/{id}/approve','Marvel\Http\Controllers\ShopController@approveWithToken');
        Route::post('staffs', 'Marvel\Http\Controllers\ShopController@addStaff');
        Route::delete('staffs/{id}', 'Marvel\Http\Controllers\ShopController@deleteStaff');
        Route::get('staffs', 'Marvel\Http\Controllers\UserController@staffs');
        Route::get('my-shops', 'Marvel\Http\Controllers\ShopController@myShops');
    }
);

Route::apiResource('reviews', CustomerReviewsController::class, [
    'only' => ['index','show']
]);
Route::group(['middleware' => ['permission:' . Permission::SUPER_ADMIN.'|'.Permission::SHAREHOLDER.'|'. Permission::CEO.'|'. Permission::MANAGER_RH.'|'. Permission::MARKETING.'|'. Permission::MANAGEMENT.'|'. Permission::LEGAL, 'auth:sanctum']], function () {
    Route::get('product/export','Marvel\Http\Controllers\ProductController@exportAllProducts');
    Route::apiResource('types', TypeController::class, [
        'only' => ['store', 'update', 'destroy']
    ]);
    Route::apiResource('premium-admin',PremiumSubscriptionsController::class,[
        'only'=>['index','show','update','destroy']
    ]);
    Route::get('admin-owner-info/{id}','Marvel\Http\Controllers\UserController@storeOwnerInfo');
    Route::apiResource('premium-plans',PremiumPlansController::class,[
        'only'=>['index','show','update','destroy',"store"]
    ]);
    Route::get('orders/export/all',[OrderController::class,'allOrdersInStore']);
    Route::apiResource('privacy-policy', PrivacyPolicyController::class,[
        'only' => ['index','show','store', 'update', 'destroy']
    ]);
    Route::apiResource('salaries', SalariesController::class,[
        'only' => ['index','show','store', 'update', 'destroy']
    ]);
    Route::apiResource('marketing', MarketingController::class, [
        'only' => ['index','show','store', 'destroy']
    ]);
    Route::put('marketing','Marvel\Http\Controllers\MarketingController@update');
    Route::apiResource('approval-tokens', ApprovalTokenController::class, [
        'only' => ['index','show','store', 'update', 'destroy']
    ]);
    Route::get('withdraws/export/all','Marvel\Http\Controllers\WithdrawController@exportWithdraws');
    Route::apiResource('withdraws', WithdrawController::class, [
        'only' => ['update', 'destroy']
    ]);
    Route::apiResource('categories', CategoryController::class, [
        'only' => ['store', 'update', 'destroy']
    ]);
    Route::apiResource('tags', TagController::class, [
        'only' => ['store', 'update', 'destroy']
    ]);
    Route::apiResource('coupons', CouponController::class, [
        'only' => ['store', 'update', 'destroy']
    ]);
    Route::apiResource('order-status', OrderStatusController::class, [
        'only' => ['store', 'update', 'destroy']
    ]);

    Route::apiResource('settings', SettingsController::class, [
        'only' => ['store']
    ]);

    Route::apiResource('users', UserController::class);
    Route::post('users/block-user', 'Marvel\Http\Controllers\UserController@banUser');
    Route::post('users/unblock-user', 'Marvel\Http\Controllers\UserController@activeUser');
    Route::get('users/export/all','Marvel\Http\Controllers\UserController@exportUsersAndOrders');
    Route::apiResource('taxes', TaxController::class);
    Route::apiResource('shippings', ShippingController::class);
    Route::post('approve-shop', 'Marvel\Http\Controllers\ShopController@approveShop');
    Route::post('disapprove-shop', 'Marvel\Http\Controllers\ShopController@disApproveShop');
    Route::post('approve-withdraw', 'Marvel\Http\Controllers\WithdrawController@approveWithdraw');

});
