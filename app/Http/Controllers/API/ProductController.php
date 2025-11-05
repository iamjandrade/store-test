<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Mostrar lista de los productos.
     */
    public function show(Request $request)
    {
        $products = Product::all();

        return ProductResource::collection($products);
    }
}
