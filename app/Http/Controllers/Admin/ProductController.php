<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')
            ->paginate(config('pagination.per_page'));

        return view('dashboards.admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return view('dashboards.admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $dirImages = "images/uploads/products";
        $imageName = time() . '-' . rand(0, 255) . '-' . $request->title . '.' .
            $request->image->getClientOriginalExtension();
        $request->image->move(public_path($dirImages), $imageName);
        $slug = Str::slug($request->title);
        $product = Product::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'slug' => $slug,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'content' => $request->content,
            'retail_price' => $request->retail_price,
            'original_price' => $request->original_price,
            'image' => asset($dirImages . '/' . $imageName),
        ]);

        return Redirect::route('admin.products.create')
            ->with('success', __('Add product successfuly'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('dashboards.admin.products.edit', compact('categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $slug = Str::slug($request->title);
        $dirImages = "images/uploads/products";
        if ($request->hasfile('image')) {
            $destination = $dirImages . $product->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $newImage = time() . '-' . rand(0, 255) . '-' .  $request->title . '.' . $extension;
            $file->move($dirImages, $newImage);
            $imageLink = asset($dirImages . '/' . $newImage);
        } else {
            $imageLink = $request->imageExist;
        }
        $product->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'slug' => $slug,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'content' => $request->content,
            'retail_price' => $request->retail_price,
            'original_price' => $request->original_price,
            'image' => $imageLink,
        ]);

        return Redirect::route('admin.products.index')
            ->with('success', __('Update product successfuly'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findorfail($id);
        $dirImages = 'images/uploads/products/';
        $destination =  $dirImages . $product->image;
        if (File::exists($destination)) {
            File::delete($destination);
        }
        $product->delete();

        return Redirect::route('admin.products.index')
            ->with('success', __('Delete product successfuly'));
    }
}
