<?php

namespace App\Repositories\Product;

use App\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    //find
    public function whereIn($column, $value);

    public function whereCategoryId($id);

    public function updateCategoryIdOfProductWhenCategoryDeleted($id, $attributes = []);
    
    public function search($column, $operator, $value, $per_page);

    public function getProductDetails($slug);

    public function getProductRatings($product_id, $rating_per_page);

    public function getRelatedProducts($product, $amount);

    public function updateAvg($product_id, $avg_rate);

    public function getQuantity($product);
}
