<?php

declare(strict_types=1);

namespace App\Helpers;

use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class StripeHelper
{
    /**
     * @throws ApiErrorException
     */
    final public static function createCheckoutSession(
        string $redirectUrl,
        string $productName,
        int $price,
        string $currency
    ): Session {
        return Session::create(
            params: [
                'success_url' => $redirectUrl,
                'payment_method_types' => ['link', 'card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'product_data' => [
                                'name' => $productName,
                            ],
                            'unit_amount' => 100 * $price,
                            'currency' => $currency,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'allow_promotion_codes' => false,
            ]
        );
    }
}
