<?php

namespace App\Repositories\Product;

use App\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    //find
    public function whereIn($column, $value);

    public function whereCategoryId($id);

    public function updateCategoryIdOfProductWhenCategoryDeleted($id, $attributes = []);
}
