<?php

namespace App\Services;

use App\Models\Product;
use App\Filters\ProductFilter;

class ProductService
{
    protected ProductFilter $filter;

    public function __construct(ProductFilter $filter)
    {
        $this->filter = $filter;
    }

    public function getFilteredProducts(array $filters)
    {
        $query = Product::query()->with('category');
        
        $query = $this->filter->apply($query, $filters);
        
        $perPage = isset($filters['per_page']) ? (int)$filters['per_page'] : 15;
        
        return $query->paginate($perPage);
    }
}