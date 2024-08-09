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
        // Set the API key for Stripe
        Stripe::setApiKey(apiKey: config(key: 'services.stripe.secret_key'));

        // Prepare the URL to redirect to after successful payment
        $redirectUrl = route(name: 'stripe.charge.success') . '?session_id={CHECKOUT_SESSION_ID}';

        // Define a unique key for rate limiting based on the authenticated user's ID
        $rateLimiterKey = 'checkout:' . auth()->id();

        // Check if the user has made too many payment attempts
        if (RateLimiter::tooManyAttempts(key: $rateLimiterKey, maxAttempts: 1)) {
            return redirect()->back()->withErrors(
                provider: [
                    'error' => 'Too many attempts. Please try again in 10 seconds.',
                ]
            );
        }

        // Record the payment attempt to enforce rate limiting
        RateLimiter::hit(key: $rateLimiterKey, decaySeconds: 10);

        // Create a new checkout session with Stripe
        $checkoutSession = StripeHelper::createCheckoutSession(
            redirectUrl: $redirectUrl,
            productName: $request->product_name,
            price: $request->price,
            currency: 'try',
        );

        // Redirect the user to the Stripe checkout page
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
        // Set the API key for Stripe
        Stripe::setApiKey(apiKey: config(key: 'services.stripe.secret_key'));

        // Retrieve the Stripe session details using the provided session ID
        $stripeSession = Session::retrieve(id: $request->session_id);

        // Return the view for the payment success page with customer email and success message
        return view(
            view: 'stripe.success',
            data: [
                'customer_email' => $stripeSession->customer_details->email,
                'success_message' => 'Your payment process has been completed. Thank you for shopping with us.',
            ]
        );
    }

}
