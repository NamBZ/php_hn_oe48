<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class CategoryController extends Controller
{
    protected $categoryRepo;

    protected $productRepo;

    public function __construct(
        CategoryRepositoryInterface $categoryRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->categoryRepo = $categoryRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $post
     * @return \Illuminate\Http\Response
     */
    public function show($slug, Request $request)
    {
        $category = $this->categoryRepo->getCategoryBySlugWithChildren($slug);
        $list_category_id = $this->categoryRepo->getCategoryID($category->id);

        $list_products = $this->productRepo->whereIn('category_id', $list_category_id);

        $search = $request->get('search');
        $sort = $request->get('sort');
        $rating = $request->get('rating');
        $minPrice = $request->get('minPrice');
        $maxPrice = $request->get('maxPrice');

        // Search
        if ($search) {
            $list_products = $list_products->where('title', 'LIKE', "%{$search}%");
        }

        // Rating
        if ($rating) {
            $list_products = $list_products->where('avg_rate', '>=', $rating);
        }

        // Price
        if ($minPrice && $maxPrice) {
            $list_products = $list_products->whereBetween('retail_price', [$minPrice, $maxPrice]);
        }

        // Sort
        if ($sort) {
            switch ($sort) {
                case 'newest':
                    $list_products = $list_products->orderBy('created_at', 'DESC');
                    break;
                case 'top_seller':
                    $list_products = $list_products->orderBy('sold', 'DESC');
                    break;
                case 'price_asc':
                    $list_products = $list_products->orderBy('retail_price');
                    break;
                case 'price_desc':
                    $list_products = $list_products->orderBy('retail_price', 'DESC');
                    break;
                default:
                    $list_products = $list_products->orderBy('created_at', 'DESC');
                    break;
            }
        }

        $list_products = $list_products->paginate(config('pagination.per_page'))->withQueryString();

        return view('products.category', [
            'category' => $category,
            'list_products' => $list_products,
        ]);
    }
}
