<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepository;
use App\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Category::class;
    }

    public function getCategoryID($parent_id)
    {
        $list_category_id[] = $parent_id;
        $list_children = $this->where('parent_id', $parent_id);

        if ($list_children->isNotEmpty()) {
            foreach ($list_children as $sub_cate) {
                $list_category_id = array_merge($list_category_id, $this->getCategoryID($sub_cate->id));
            }
        }

        return $list_category_id;
    }

    public function getCategoryBySlugWithChildren($slug)
    {
        return $this->model->where('slug', $slug)->with('children')->firstOrFail();
    }

    public function where($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    public function loadParent($id)
    {
        return $this->model->load('parent')
            ->whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getDefaultCategoryId()
    {
        return $this->model->first()->id;
    }

    public function getParentCategoriesWithChild()
    {
        return $this->model->whereNull('parent_id')->with('children')->get();
    }
}
