<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use Tests\ModelTestCase;

class CategoryTest extends ModelTestCase
{
    protected function initModel()
    {
        return new Category();
    }

    public function testModelConfiguration()
    {
        $fillable = [
            'name',
            'slug',
            'parent_id',
        ];

        $this->runConfigurationAssertions(
            $this->model,
            [
                'fillable' => $fillable,
            ]
        );
    }

    public function testProductsRelation()
    {
        $relation = $this->model->products();
        $related = new Product();

        $this->assertHasManyRelation(
            $relation,
            $this->model,
            $related
        );
    }

    public function testCategoryHasManyChildCategories()
    {
        $relation = $this->model->children();
        $related = new Category();
        $key = 'parent_id';

        $this->assertHasManyRelation(
            $relation,
            $this->model,
            $related,
            $key
        );
    }

    public function testCategoryHasParentCategory()
    {
        $relation = $this->model->parent();
        $related = $this->model;
        $key = 'parent_id';
        $parent = 'id';

        $this->assertBelongsToRelation(
            $relation,
            $this->model,
            $related,
            $key,
            $parent
        );
    }

    public function testIsParentCategoryTrue()
    {
        $parent = $this->model;

        $this->assertTrue($parent->isParent());
    }

    public function testIsParentCategoryFalse()
    {
        $this->model->parent_id = 1;
        $parent = $this->model;

        $this->assertFalse($parent->isParent());
    }
}
