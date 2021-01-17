<?php


namespace app\controllers\admin;


use app\models\AppModel;
use app\models\UserModel;
use core\base\Controller;

class AppController extends Controller
{
    /** @var string|bool шаблон */
    public $layout = 'admin';

    public function __construct($route)
    {
        parent::__construct($route);
        if ($route['action'] !== 'login-admin' && !UserModel::isAdmin()) {
            redirect(ADMIN . '/user/login-admin'); // UserController::loginAdminAction
        }
        new AppModel();
    }
}