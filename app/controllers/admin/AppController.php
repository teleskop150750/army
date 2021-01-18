<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\UserModel;
use core\base\Controller;
use core\exceptions\IdException;

class AppController extends Controller
{
    /** @var string|bool шаблон */
    public $layout = 'admin';

    public function __construct($route)
    {
        parent::__construct($route);

        // не настранице региситации или не админ?
        if ($route['action'] !== 'login-admin' && !UserModel::isAdmin()) {
            redirect(ADMIN . '/user/login-admin'); // UserController::loginAdminAction
        }

        new AppModel();
    }

    /**
     * получить id из запроса
     * @param bool $get это get
     * @param string $id имя
     * @return int id
     * @throws IdException
     */
    public function getRequestID($get = true, $id = 'id'): int
    {
        if ($get) {
            $data = $_GET;
        } else {
            $data = $_POST;
        }

        $id = $data[$id] ?? null;

        if (is_null($id)) {
            throw new IdException('Страница не найдена', 404);
        }

        return (int)$id;
    }
}
