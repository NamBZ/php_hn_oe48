<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Rating;

class ProductController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $post
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::where('slug', '=', $slug)->firstOrFail();
        $ratings = $product->ratings()->paginate(config('pagination.rating_per_page'));
        $related_products = Product::where('category_id', $product->category->id)
            ->where('id', '!=', $product->id)
            ->take(config('pagination.related'))
            ->get();

        return view('products.details', [
            'product' => $product,
            'ratings' => $ratings,
            'related_products' => $related_products,
        ]);
    }
}
