<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

final class ProductController extends Controller
{
    /**
     * Display the product list page.
     *
     * This method returns the view for the product list page. It is used to render
     * the view that displays all the products available for purchase.
     *
     * @return View
     */
    final public function displayProductListPage(): View
    {
        return view(view: 'products.list');
    }
}
