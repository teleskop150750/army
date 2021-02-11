<?php

namespace app\controllers\base;

use app\models\AppModel;
use core\base\Controller;

class AppBaseController extends Controller
{
    public function __construct($route)
    {
        parent::__construct($route);
        new AppModel();
    }
}
