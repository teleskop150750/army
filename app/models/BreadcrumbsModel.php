<?php

namespace app\models;

use core\App;
use core\exceptions\ParameterException;

class BreadcrumbsModel
{
    /**
     * получить хлебные крошки
     * @param int $category_id id категории
     * @param string $name название продукта
     * @return string HTML крошек
     * @throws ParameterException
     */
    public static function getBreadcrumbs(int $category_id, string $name = ''): string
    {
        $cats = App::$app->getProperty('cats');
        $breadcrumbs_array = self::getParts($cats, $category_id);
        $breadcrumbs = "<li><a href='" . PATH . "'>Главная</a></li>";

        if ($breadcrumbs_array) {
            foreach ($breadcrumbs_array as $alias => $title) {
                $breadcrumbs .= "<li><a href='" . PATH . "/category/{$alias}'>$title</a></li>";
            }
        }

        if ($name) {
            $breadcrumbs .= "<li>{$name}</li>";
        }
        return $breadcrumbs;
    }

    /**
     * получить крошки
     * @param array $cats категрии
     * @param int $id id категории
     * @return array|false
     */
    public static function getParts(array $cats, int $id)
    {
        if (!$id) {
            return false;
        }

        $breadcrumbs = [];

        foreach ($cats as $key => $value) {
            if (isset($cats[$id])) {
                $breadcrumbs[$cats[$id]['alias']] = $cats[$id]['title'];
                $id = $cats[$id]['parent_id'];
            } else {
                break;
            }
        }

        return array_reverse($breadcrumbs, true);
    }
}
