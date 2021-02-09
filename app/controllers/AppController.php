<?php

namespace app\controllers;

use app\controllers\base\AppBaseController;
use app\models\AppModel;
use app\widgets\currency\CurrencyWidgetModel;
use core\App;
use core\Cache;
use RedBeanPHP\R;

class AppController extends AppBaseController
{
    /**
     * AppController constructor.
     * @param array $route
     */
    public function __construct(array $route)
    {
        parent::__construct($route);
        new AppModel();
    }
}
