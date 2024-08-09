@extends('layouts.app')

@section('title', 'Payment Success')

@section('style')
    <style>
        .bg {
            background-color: #ffc72c;
            border-color: #ffd98e #ffbe3d #de9300;
        }

        .bg:hover {
            background-color: #f7c027;
        }
    </style>
@endsection

@section('content')
    <div class="container text-center mt-5 pt-5">
        <img src="{{ asset('check-circle.svg') }}" class="tick-img" alt="tick-img">
        <h1 class="text-center">Thank you for making payment</h1>
        <h4 class="text-center mt-3">We sent your invoice to your email account : {{ $customer_email }}</h4>
        <h6 class="text-center mt-3">{{ $success_message }}</h6>

        <a href="{{ route(name: 'display.stripe.charge.page') }}" class="btn mt-5 bg">Continue Shopping</a>
    </div>
@endsection
