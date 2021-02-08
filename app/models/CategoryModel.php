<?php

namespace app\models;

use core\App;
use core\exceptions\CategoryException;
use core\exceptions\ParameterException;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class CategoryModel extends AppModel
{
    /**
     * получить текущуюю категорию
     * @param string $alias
     * @return object
     * @throws CategoryException
     */
    public function getCategory(string $alias): object
    {
        $category = R::findOne('category', 'alias = ?', [$alias]);
        if (!$category) {
            throw new CategoryException('Страница не найдена', 404);
        }
        return $category;
    }

    /**
     * получить id вложенных категорий
     * @param int $id id текущуй категории
     * @return string
     * @throws ParameterException
     */
    public function getIds(int $id): string
    {
        $cats = App::$app->getProperty('cats');
        $ids = '';

        foreach ($cats as $key => $cat) {
            if ((int)$cat['parent_id'] === $id) {
                $ids .= $key . ',';
                $ids .= $this->getIds($key);
            }
        }

        return $ids;
    }

    /**
     * получить количество записей
     * @param string $ids id категорий
     * @param string $sql_part
     * @return int
     */
    public function getTotal(string $ids, string $sql_part): int
    {
        return R::count('product', "category_id IN ($ids) $sql_part");
    }

    /**
     * получить товары на странице
     * @param string $ids id категорий
     * @param string $sql_part
     * @param int $start страница
     * @param int $perPage количество на странице
     * @return array товары
     */
    public function getProducts(string $ids, string $sql_part, int $start, int $perPage): array
    {
        return R::find('product', "category_id IN ($ids) $sql_part  AND status = '1' LIMIT $start, $perPage");
    }
}
