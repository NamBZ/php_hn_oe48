<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Facades\Redirect;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    protected $categoryRepository;

    protected $productRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->categoryRepository
            ->paginate(config('pagination.per_page'));

        return view('dashboards.admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->categoryRepository->getAll();

        return view('dashboards.admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->categoryRepository->create($request->validated());

        return Redirect::route('admin.categories.index')
            ->with('success', __('Add category successfuly'));
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
        $category = $this->categoryRepository->findOrFail($id);
        $categories = $this->categoryRepository->loadParent($id);

        return view('dashboards.admin.categories.edit', compact('category', 'categories'));
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
        $category = $this->categoryRepository->update($id, $request->validated());

        return Redirect::route('admin.categories.index')
            ->with('success', __('Update category successfuly'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->findOrFail($id);
        $defaultCate = $this->categoryRepository->getDefaultCategoryId();
        $this->productRepository->updateCategoryIdOfProductWhenCategoryDeleted($id, [
            'category_id' => $defaultCate,
        ]);
        if ($category->id == $defaultCate) {
            return Redirect::route('admin.categories.index')
                ->with('info', __('This category cannot be delete because needed'));
        }
        $this->categoryRepository->delete($category->id);

        return Redirect::route('admin.categories.index')
            ->with('success', __('Delete category successfuly'));
    }
}
