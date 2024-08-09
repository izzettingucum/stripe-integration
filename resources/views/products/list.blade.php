@extends('layouts.app')

@section('style')
    <style>
        .product-card {
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 1rem;
        }

        .product-card h5 {
            font-size: 1.25rem;
        }
    </style>
@endsection

@if ($errors->any() || session('message'))
    <div class="alert {{ $errors->any() ? 'alert-danger' : 'alert-success' }}">
        <ul>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @else
                <li>{{ session('message') }}</li>
            @endif
        </ul>
    </div>
@endif

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Product List</h2>
        <div class="row">
            <!-- Product 1 -->
            <div class="col-md-4">
                <div class="product-card">
                    <img src="{{ fake()->imageUrl(450, 450) }}" alt="Product 1">
                    <h5 class="product-name">{{ $name1 = fake()->text(maxNbChars: 20) }}</h5>
                    <p class="price">{{ $price1 = fake()->numberBetween(100, 1000) }} TL</p>
                    <button class="btn btn-primary buy-button"
                            data-name="{{ $name1 }}"
                            data-price="{{ $price1 }}">Buy
                    </button>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="col-md-4">
                <div class="product-card">
                    <img src="{{ fake()->imageUrl(450, 450) }}" alt="Product 2">
                    <h5 class="product-name">{{ $name2 = fake()->text(maxNbChars: 20) }}</h5>
                    <p class="price">{{ $price2 = fake()->numberBetween(100, 1000) }} TL</p>
                    <button class="btn btn-primary buy-button"
                            data-name="{{ $name2 }}"
                            data-price="{{ $price2 }}">Buy
                    </button>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="col-md-4">
                <div class="product-card">
                    <img src="{{ fake()->imageUrl(450, 450) }}" alt="Product 3">
                    <h5 class="product-name">{{ $name3 = fake()->text(maxNbChars: 20) }} TL</h5>
                    <p class="price">{{ $price3 = fake()->numberBetween(100, 1000) }} </p>
                    <button class="btn btn-primary buy-button"
                            data-name="{{ $name3 }}"
                            data-price="{{ $price3 }}">Buy
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        document.querySelectorAll('.buy-button').forEach(button => {
            button.addEventListener('click', function () {
                const productName = this.getAttribute('data-name');
                const productPrice = this.getAttribute('data-price');

                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/charge';

                // Add CSRF token
                const csrfToken = '{{ csrf_token() }}';
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add product name input
                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = 'product_name';
                nameInput.value = productName;
                form.appendChild(nameInput);

                // Add product price input
                const priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = 'price';
                priceInput.value = productPrice;
                form.appendChild(priceInput);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
@endsection

