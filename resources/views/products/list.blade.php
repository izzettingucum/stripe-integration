<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
</head>
<body>
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
                        data-price="{{ $price1 }}">Buy</button>
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
                        data-price="{{ $price2 }}">Buy</button>
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
                        data-price="{{ $price3 }}">Buy</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.querySelectorAll('.buy-button').forEach(button => {
        button.addEventListener('click', function() {
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

</body>
</html>
