<?php

namespace App\Repositories\Product;

use App\Repositories\BaseRepository;
use App\Models\Product;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Product::class;
    }

    public function whereIn($column, $value)
    {
        return $this->model->whereIn($column, $value);
    }

    public function whereCategoryId($id)
    {
        return $this->model->whereCategoryId($id);
    }

    public function updateCategoryIdOfProductWhenCategoryDeleted($id, $attributes = [])
    {
        $result = $this->whereCategoryId($id);
        if ($result) {
            $result->update($attributes);

            return $result;
        }

        return false;
    }
    
    public function getProductDetails($slug)
    {
        $result = $this->model->where('slug', $slug)->firstOrFail();

        return $result;
    }

    public function getProductRatings($product_id, $rating_per_page)
    {
        $ratings = $this->findOrFail($product_id)
            ->ratings()
            ->paginate($rating_per_page);

        return $ratings;
    }

    public function getRelatedProducts($product, $amount)
    {
        $related_products = $this->model
            ->where('category_id', $product->category->id)
            ->where('id', '!=', $product->id)
            ->take($amount)
            ->get();

        return $related_products;
    }

    public function search($column, $operator, $value, $per_page)
    {
        $result = $this->model
            ->where($column, $operator, $value)
            ->paginate($per_page);

        return $result;
    }

    public function updateAvg($product_id, $avg_rate)
    {
        $product = $this->find($product_id);

        $product->avg_rate = $avg_rate;

        if ($product->save()) {
            return true;
        }

        return false;
    }

    public function getQuantity($product)
    {
        return $product->pivot->quantity;
    }
    
    public function updateProductQuantity($product_id, $quantity = 0, $sold = 0)
    {
        $product = $this->find($product_id);

        $product->quantity = $quantity;
        $product->sold = $sold;

        if ($product->save()) {
            return true;
        }

        return false;
    }
}
