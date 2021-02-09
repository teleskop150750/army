<?php

namespace app\controllers;

class MainController extends AppController
{
    public function indexAction(): void
    {
        $this->layout = 'main';
        $this->view = 'index';
        $this->setMeta('Военкомат', 'Описание...', 'Ключевые слова...');
    }
}
