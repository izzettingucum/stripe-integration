<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Laravel Stripe Integration

This project demonstrates a basic integration of the Stripe payment gateway with Laravel. It includes a simple setup for processing payments using Stripe Checkout, handling payment sessions, and displaying a success message to users after a successful payment.

### Key Features

- Basic Stripe Checkout integration for payment processing.
- Rate limiting to prevent multiple checkout attempts within a short period.
- Success page that confirms payment and shows invoice details.

## Installation

To set up this project locally, follow these steps:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/your-username/stripe-integration-project.git
    cd stripe-integration-project
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Set up environment variables:**

   Copy the example environment file and set your Stripe API keys:

    ```bash
    cp .env.example .env
    ```

   Update your `.env` file with your Stripe credentials:

    ```dotenv
    STRIPE_SECRET_KEY=your-stripe-secret-key
    STRIPE_PUBLISHABLE_KEY=your-stripe-publishable-key
    ```

4. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

5. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

6. **Start the development server:**

    ```bash
    php artisan serve --host=localhost
    ```

7. **Visit the application:**

   Open `http://localhost:8000` in your web browser.

## Usage

### Checkout Session Creation

Use the `chargePayment` method in the `PaymentController` to create a Stripe Checkout session. This method is triggered when a user initiates a payment for a product.

### Success Page

After payment completion, users are redirected to a success page that displays their email and a confirmation message.

### Rate Limiting

The `RateLimiter` is used in the `chargePayment` method to limit checkout attempts and avoid abuse. Users must wait 10 seconds before making another attempt.

## Contributing

If you'd like to contribute to this project, please fork the repository and submit a pull request with your changes.


