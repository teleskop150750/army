<?php

namespace app\controllers;

use app\models\MainModel;

class MainController extends AppController
{
    public function indexAction(): void
    {
        $main_model = new MainModel();
        $brands = $main_model->getBrands();
        $hits = $main_model->getHits();

        $this->setMeta('TITLE', 'Описание...', 'Ключевые слова...');
        $this->setData(compact('brands', 'hits'));
    }
}
