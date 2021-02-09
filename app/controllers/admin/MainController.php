<?php

namespace app\controllers\admin;

use app\models\admin\MainModel;

class MainController extends AdminController
{
    public function indexAction(): void
    {
        $main_model = new MainModel();
        $countArticles = $main_model->getCountArticles();
        $countUsers = $main_model->getCountUsers();
        $countCategories = $main_model->getCountCategories();

        $this->setMeta('Панель управления');
        $this->setData(compact('countArticles', 'countUsers', 'countCategories'));
    }
}
