<?php

namespace app\models\admin;

use app\models\base\AppBaseModel;
use RedBeanPHP\R;

class MainModel extends AdminModel
{
    public function getCountArticles(): int
    {
        return R::count('article', "status = '1'");
    }

    public function getCountUsers(): int
    {
        return R::count('user');
    }

    public function getCountCategories(): int
    {
        return R::count('category');
    }
}
