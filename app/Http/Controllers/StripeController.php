<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\StripeHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

final class StripeController extends Controller
{
    /**
     * @throws ApiErrorException
     */
    final public function chargePayment(Request $request): RedirectResponse
    {
        Stripe::setApiKey(apiKey: config(key: 'services.stripe.secret_key'));

        $redirectUrl = route(
            name: 'stripe.charge.success',
            parameters: ['session_id' => '{CHECKOUT_SESSION_ID}']
        );

        $rateLimiterKey = 'checkout:' . auth()->id();

        if (RateLimiter::tooManyAttempts(key: $rateLimiterKey, maxAttempts: 1)) {
            return redirect()->back()->withErrors(
                provider: [
                    'error' => 'Too many attempts. Please try again in 10 seconds.',
                ]
            );
        }

        RateLimiter::hit(key: $rateLimiterKey, decaySeconds: 10);

        $checkoutSession = StripeHelper::createCheckoutSession(
            redirectUrl: $redirectUrl,
            productName: $request->product_name,
            price: (int) $request->price,
            currency: 'try',
        );

        return redirect(to: $checkoutSession->url);
    }

    final public function displaySuccessPage(Request $request): View
    {
        // TODO: This endpoint will be edited soon
        return view(view: 'stripe.success');
    }
}
