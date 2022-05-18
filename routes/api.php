<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Minishlink\WebPush\VAPID;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v1/push-notifications', function (Request $request) {
    $subscription = $request->getContent();
    $subscription = Subscription::create(json_decode($subscription, true));
    $auth = [
        'VAPID' => [
            'subject' => 'mailto:me@website.com', // can be a mailto: or your website address
            'publicKey' => config('services.web-push.publicKey'), // (recommended) uncompressed public key P-256 encoded in Base64-URL
            'privateKey' => config('services.web-push.privateKey'), // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
        ],
    ];

    $webPush = new WebPush($auth);

    $report = $webPush->sendOneNotification(
        $subscription,
        "Hello! 👋"
    );

    // handle eventual errors here, and remove the subscription from your server if it is expired
    $endpoint = $report->getRequest()->getUri()->__toString();
    $message = "";

    if ($report->isSuccess()) {
        $message = "[v] Message sent successfully for subscription {$endpoint}.";
    } else {
        $message = "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
    }

    return response()->json([
        "message" => $message
    ]);
});
