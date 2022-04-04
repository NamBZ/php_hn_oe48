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
}
