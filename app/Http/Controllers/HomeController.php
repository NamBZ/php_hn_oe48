<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $list_products = $this->productRepo->paginate(config('pagination.per_page'));
        $categories = $this->categoryRepo->getParentCategoriesWithChild();

        return view('home', [
            'categories' => $categories,
            'list_products' => $list_products,
        ]);
    }

    public function changeLanguage($language)
    {
        session()->put('locale', $language);

        return redirect()->back();
    }
}
