<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $search = request()->query('query');
        if ($search) {
            $list_products = Product::where('title', 'LIKE', "%{$search}%")
            ->paginate(config('pagination.per_page'));
        } else {
            return redirect()->back();
        }

        return view('search', [
            'list_products' => $list_products,
        ]);
    }
}
