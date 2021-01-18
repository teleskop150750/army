<?php

namespace app\controllers\admin;

use app\models\admin\MainModel;

class MainController extends AppController
{
    public function indexAction(): void
    {
        $main_model = new MainModel();
        $countNewOrders = $main_model->getCountNewOrders();
        $countUsers = $main_model->getCountUsers();
        $countProducts = $main_model->getCountProducts();
        $countCategories = $main_model->getCountCategories();

        $this->setMeta('Панель управления');
        $this->setData(compact('countNewOrders', 'countCategories', 'countProducts', 'countUsers'));
    }
}
