<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductController extends Controller
{
    protected $productRepo;

    public function __construct(
        ProductRepositoryInterface $productRepo
    ) {
        $this->productRepo = $productRepo;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $post
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = $this->productRepo->getProductDetails($slug);

        $ratings = $this->productRepo
            ->getProductRatings(
                $product->id,
                config('pagination.rating_per_page')
            );

        $related_products = $this->productRepo
            ->getRelatedProducts(
                $product,
                config('pagination.related')
            );

        return view('products.details', [
            'product' => $product,
            'ratings' => $ratings,
            'related_products' => $related_products,
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->get('query');
        if ($search) {
            $list_products = $this->productRepo
                ->search(
                    'title',
                    'LIKE',
                    "%{$search}%",
                    config('pagination.per_page')
                );
        } else {
            return redirect()->back();
        }

        return view('search', [
            'list_products' => $list_products,
        ]);
    }
}
