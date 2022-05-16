<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreCategoryRequest;
use App\Http\Requests\Api\Admin\UpdateCategoryRequest;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories = $this->categoryRepo
                ->paginate(config('pagination.per_page'));

            return response()->json([
                'status' => 'success',
                'data' => $categories,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $categories = $this->categoryRepo->getAll();

            return response()->json([
                'status' => 'success',
                'data' => $categories,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $created = $this->categoryRepo->create($request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $created,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $edit_category = $this->categoryRepo->findOrFail($id);
            $categories = $this->categoryRepo->loadParent($id);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'info' => $edit_category,
                    'list_parents' => $categories,
                ],
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            if (in_array($request->parent_id, $this->categoryRepo->getCategoryID($id))) {
                return response()->json([
                    'status' => 'error',
                    'data' => __('Update failed'),
                ], 200);
            }

            $category = $this->categoryRepo->update($id, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => __('Update category successfuly'),
                'data' => $category,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            if (empty($this->categoryRepo->find($id))) {
                return response()->json([
                    'status' => 'error',
                    'data' => __('Delete failed'),
                ], 200);
            }

            $defaultCate = $this->categoryRepo->getDefaultCategoryId();

            if ($id == $defaultCate) {
                return response()->json([
                    'status' => 'error',
                    'data' => __('This category cannot be delete because needed'),
                ], 200);
            }

            $this->productRepo->updateCategoryIdOfProductWhenCategoryDeleted($id, [
                'category_id' => $defaultCate,
            ]);

            $this->categoryRepo->delete($id);

            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'data' => __('Delete category successfuly'),
        ], 200);
    }
}
