<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $post
     * @return \Illuminate\Http\Response
     */
    public function detail($slug)
    {
        $product = Product::where('slug', '=', $slug)->firstOrFail();

        return 1;
    }
}
