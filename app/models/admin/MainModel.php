<?php

namespace app\models\admin;

use app\models\base\AppBaseModel;
use RedBeanPHP\R;

class MainModel extends AdminModel
{

    /**
     * получить количество новых заказов
     * @return int количество новых заказов
     */
    public function getCountNewOrders(): int
    {
        return R::count('order', "status = '0'");
    }

    /**
     * получить количество пользователей
     * @return int количество пользователей
     */
    public function getCountUsers(): int
    {
        return R::count('user');
    }

    /**
     * получить количество продуктов
     * @return int количество продуктов
     */
    public function getCountProducts(): int
    {
        return R::count('product');
    }

    /**
     * получить количество категорий
     * @return int количество категорий
     */
    public function getCountCategories(): int
    {
        return R::count('category');
    }
}
