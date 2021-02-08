<?php

namespace app\models;

use RedBeanPHP\R;

class SearchModel extends AppModel
{
    /**
     * получить количество продуктов
     * @param string $query запрос
     * @return int количество продуктов
     */
    public function getTotal(string $query): int
    {
        return R::count('product', "title LIKE ?", ["%{$query}%"]);
    }

    /**
     * получить продукты
     * @param string $query запрос
     * @param int $start начинать с
     * @param int $perPage количество
     * @return array продукты
     */
    public function getProducts(string $query, int $start, int $perPage): array
    {
        return R::find('product', "title LIKE ? AND status = '1' LIMIT $start, $perPage", ["%{$query}%"]);
    }
}
