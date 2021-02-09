<?php

namespace app\models\admin;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class CategoryModel extends AdminModel
{
    public array $attributes = [
        'title' => '',
    ];

    public array $rules = [
        'required' => [
            ['title'],
        ],
    ];

    public function getCategories(): ?array
    {
        return R::getAll("
            SELECT category.*
            FROM category");
    }

    public function getCategory(int $id): object
    {
        return R::load('category', $id);
    }

    public function deleteCategory(int $id): void
    {
        R::exec("DELETE FROM category WHERE id  = ?", [$id]);
    }
}
