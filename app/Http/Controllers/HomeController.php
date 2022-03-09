<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $list_products = Product::orderBy('created_at', 'DESC')
            ->paginate(config('pagination.per_page'));
        $categories = Category::whereNull('parent_id')->with('children')->get();

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
