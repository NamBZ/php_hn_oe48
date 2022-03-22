<?php

namespace App\Repositories\Category;

use App\Repositories\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    //find all child cate id
    public function getCategoryID($parent_id);

    public function getCategoryBySlugWithChildren($slug);

    public function where($column, $value);
}
