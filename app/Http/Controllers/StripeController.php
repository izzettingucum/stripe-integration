<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\StripeHelper;
use App\Http\Requests\ChargePaymentRequest;
use App\Http\Requests\SuccessPaymentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

final class StripeController extends Controller
{
    /**
     * Process a payment charge by creating a Stripe checkout session.
     *
     * This method sets the API key for Stripe, creates a checkout session with
     * Stripe, and handles rate limiting to prevent too many payment attempts in a short
     * period of time. It then redirects the user to the Stripe checkout page.
     *
     * @param ChargePaymentRequest $request The incoming request containing product information and price.
     *
     * @return RedirectResponse Redirects to the Stripe checkout page.
     *
     * @throws ApiErrorException If an error occurs while interacting with the Stripe API.
     */
    final public function chargePayment(ChargePaymentRequest $request): RedirectResponse
    {
        Stripe::setApiKey(apiKey: config(key: 'services.stripe.secret_key'));

        $redirectUrl = route(name: 'stripe.charge.success') . '?session_id={CHECKOUT_SESSION_ID}';

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

    /**
     * Display the success page after a successful payment.
     *
     * This method retrieves the Stripe checkout session details using the provided
     * session ID, then returns a view showing a success message and the customer's
     * email address. It is intended to be called after a successful payment process.
     *
     * @param SuccessPaymentRequest $request The incoming request containing the session ID.
     *
     * @return View Returns the view for the payment success page, including customer email and success message.
     *
     * @throws ApiErrorException If an error occurs while retrieving the Stripe session.
     */
    final public function displaySuccessPage(SuccessPaymentRequest $request): View
    {
        Stripe::setApiKey(apiKey: config(key: 'services.stripe.secret_key'));

        $stripeSession = Session::retrieve(id: $request->session_id);

        return view(
            view: 'stripe.success',
            data: [
                'customer_email' => $stripeSession->customer_details->email,
                'success_message' => 'Your payment process has been completed. Thank you for shopping with us.',
            ]
        );
    }
}
