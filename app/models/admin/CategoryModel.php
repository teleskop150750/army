<?php

namespace app\models\admin;

use app\models\base\AppBaseModel;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class CategoryModel extends AdminModel
{
    public array $attributes = [
        'title' => '',
        'parent_id' => '',
        'keywords' => '',
        'description' => '',
        'alias' => '',
    ];

    public array $rules = [
        'required' => [
            ['title'],
        ]
    ];

    /**
     * получить количество потомков
     * @param int $id id категории
     * @return int количество потомков
     */
    public function getCountChildren(int $id): int
    {
        return R::count('category', 'parent_id = ?', [$id]);
    }

    /**
     * получить количество товаров
     * @param int $id id категории
     * @return int количество товаров
     */
    public function getCountProducts(int $id): int
    {
        return R::count('product', 'category_id = ?', [$id]);
    }

    /**
     * удлаить категорию
     * @param int $id id категории
     */
    public function deleteCategory(int $id): void
    {
        $category = R::load('category', $id);
        R::trash($category);
    }

    /**
     * записать alias
     * @param int $id id категории
     * @param string $alias alias
     * @throws SQL
     */
    public function setAlias(int $id, string $alias): void
    {
        $cat = R::load('category', $id);
        $cat->alias = $alias;
        R::store($cat);
    }

    /**
     * получить категорию
     * @param int $id id категории
     * @return object категория
     */
    public function getCategory(int $id): object
    {
        return R::load('category', $id);
    }
}
