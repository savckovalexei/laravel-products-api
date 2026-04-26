<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
use App\Services\ProductService;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Products API",
 *     version="1.0.0",
 *     description="API для работы с товарами. Поддерживает фильтрацию, поиск, сортировку и пагинацию.",
 *     @OA\Contact(
 *         email="admin@example.com",
 *         name="Support"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8080",
 *     description="Local server"
 * )
 * 
 * @OA\Tag(
 *     name="Products",
 *     description="API endpoints для работы с товарами"
 * )
 * 
 * @OA\Schema(
 *     schema="Product",
 *     description="Модель товара",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="iPhone 15 Pro Max"),
 *     @OA\Property(property="price", type="number", format="float", example=99999.99),
 *     @OA\Property(property="category_id", type="integer", example=1),
 *     @OA\Property(property="in_stock", type="boolean", example=true),
 *     @OA\Property(property="rating", type="number", format="float", example=4.8),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01 12:00:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01 12:00:00"),
 *     @OA\Property(
 *         property="category",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Электроника"),
 *         @OA\Property(property="description", type="string", example="Все что связано с электроникой")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="ProductsResponse",
 *     description="Ответ с пагинацией",
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product")),
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="last_page", type="integer", example=10),
 *     @OA\Property(property="per_page", type="integer", example=15),
 *     @OA\Property(property="total", type="integer", example=150)
 * )
 * 
 * @OA\Schema(
 *     schema="ValidationError",
 *     description="Ошибка валидации",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Validation errors"),
 *     @OA\Property(property="errors", type="object")
 * )
 */

class ProductController extends Controller
{
     protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

     /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Получить список товаров",
     *     description="Возвращает список товаров с возможностью фильтрации, поиска, сортировки и пагинации",
     *     operationId="getProductsList",
     *     tags={"Products"},
     *     
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Поиск по названию товара (подстрока)",
     *         required=false,
     *         example="iPhone",
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     
     *     @OA\Parameter(
     *         name="price_from",
     *         in="query",
     *         description="Минимальная цена",
     *         required=false,
     *         example=1000,
     *         @OA\Schema(type="number", format="float", minimum=0)
     *     ),
     *     
     *     @OA\Parameter(
     *         name="price_to",
     *         in="query",
     *         description="Максимальная цена",
     *         required=false,
     *         example=50000,
     *         @OA\Schema(type="number", format="float", minimum=0)
     *     ),
     *     
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="ID категории товара",
     *         required=false,
     *         example=1,
     *         @OA\Schema(type="integer")
     *     ),
     *     
     *     @OA\Parameter(
     *         name="in_stock",
     *         in="query",
     *         description="Наличие на складе (1 или 0, true или false)",
     *         required=false,
     *         example=1,
     *         @OA\Schema(type="boolean")
     *     ),
     *     
     *     @OA\Parameter(
     *         name="rating_from",
     *         in="query",
     *         description="Минимальный рейтинг (от 0 до 5)",
     *         required=false,
     *         example=4,
     *         @OA\Schema(type="number", format="float", minimum=0, maximum=5)
     *     ),
     *     
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Сортировка товаров",
     *         required=false,
     *         example="price_asc",
     *         @OA\Schema(
     *             type="string",
     *             enum={"price_asc", "price_desc", "rating_desc", "newest"},
     *             default="newest"
     *         )
     *     ),
     *     
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Номер страницы",
     *         required=false,
     *         example=1,
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество товаров на странице (макс 100)",
     *         required=false,
     *         example=15,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/ProductsResponse")
     *     ),
     *     
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     
     *     @OA\Response(
     *         response=500,
     *         description="Внутренняя ошибка сервера",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error")
     *         )
     *     )
     * )
     */

    public function index(ProductFilterRequest $request)
    {
 
    $products = $this->productService->getFilteredProducts($request->validated());
        
        return response()->json([
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
        ]);
    }

}