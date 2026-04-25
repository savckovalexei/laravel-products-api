<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProductFilter implements FilterInterface
{
    public function apply(Builder $query, array $filters): Builder
    {
        // Поиск по name (LIKE)
        if (!empty($filters['q'])) {
            $query->where('name', 'LIKE', '%' . $filters['q'] . '%');
        }

        // Фильтр по цене
        if (isset($filters['price_from'])) {
            $query->where('price', '>=', (float)$filters['price_from']);
        }
        if (isset($filters['price_to'])) {
            $query->where('price', '<=', (float)$filters['price_to']);
        }

        // Фильтр по категории
        if (!empty($filters['category_id'])) {
            $query->where('category_id', (int)$filters['category_id']);
        }

        // Фильтр по наличию
        if (isset($filters['in_stock'])) {
            $query->where('in_stock', filter_var($filters['in_stock'], FILTER_VALIDATE_BOOLEAN));
        }

        // Фильтр по рейтингу
        if (isset($filters['rating_from'])) {
            $query->where('rating', '>=', (float)$filters['rating_from']);
        }

        // Сортировка
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating_desc':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }
}