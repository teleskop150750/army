<?php

namespace app\models\admin;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class ProductModel extends AdminModel
{
    public array $attributes = [
        'title' => '',
        'category_id' => '',
        'keywords' => '',
        'description' => '',
        'price' => '',
        'old_price' => '',
        'content' => '',
        'status' => '',
        'hit' => '0',
        'alias' => '',
    ];

    public array $rules = [
        'required' => [
            ['title'],
            ['category_id'],
            ['price'],
        ],
        'integer' => [
            ['category_id'],
        ],
    ];

    /**
     * получить количество
     * @return int количество
     */
    public function getTotal(): int
    {
        return R::count('product');
    }

    /**
     * получить продукты
     * @param int $start начало
     * @param int $perPage количество
     * @return array|null
     */
    public function getProducts(int $start, int $perPage): ?array
    {
        return R::getAll("
        SELECT product.*,
               category.title AS cat 
        FROM product 
        JOIN category ON category.id = product.category_id 
        ORDER BY product.title 
        LIMIT $start, $perPage
        ");
    }

    /**
     * Записать алиас
     * @param int $id id товара
     * @param string $alias алиас
     * @throws SQL
     */
    public function setAliasProduct(int $id, string $alias): void
    {
        /** @var OODBBean|object $product */
        $product = R::load('product', $id);
        $product->alias = $alias;
        R::store($product);
    }
}
