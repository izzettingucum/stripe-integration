<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class ProductController extends Controller
{
    final public function displayProductListPage(): View
    {
        return view(view: 'products.list');
    }
}
