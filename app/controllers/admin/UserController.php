<?php

namespace app\controllers\admin;

use app\models\admin\UserModel;
use app\models\base\UserBaseModel;
use core\exceptions\IdException;
use core\libs\Pagination;
use RedBeanPHP\RedException\SQL;

class UserController extends AdminController
{
    public function indexAction(): void
    {
        $user_model = new UserModel();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 3;
        $total = $user_model->getTotal();
        $pagination = new Pagination($page, $perPage, $total);
        $start = $pagination->getStart();

        $users = $user_model->getUsers($start, $perPage);
        $this->setMeta('Список пользователей');
        $this->setData(compact('users', 'pagination', 'total'));
    }

    public function addAction(): void
    {
        $this->setMeta('Новый пользователь');
    }

    /**
     * @throws SQL|IdException
     */
    public function editAction(): void
    {
        $user_model = new UserModel();

        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $data = $_POST;
            $user_model->load($data);

            // параль не передан?
            if (!$user_model->attributes['password']) {
                unset($user_model->attributes['password']);
            } else {
                $user_model->attributes['password'] = password_hash(
                    $user_model->attributes['password'],
                    PASSWORD_DEFAULT
                );
            }

            // данные не валидны?
            if (!$user_model->validate($data) || !$user_model->checkUnique()) {
                $user_model->getErrors();
                redirect();
            }

            // данные обновены?
            if ($user_model->update('user', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
            }
            redirect();
        }

        $user_id = $this->getRequestID();
        $user = $user_model->getUser($user_id);

        $orders = $user_model->getOrders($user_id);

        $this->setMeta('Редактирование профиля пользователя');
        $this->setData(compact('user', 'orders'));
    }

    public function loginAdminAction(): void
    {
        if (!empty($_POST)) {
            $user = new UserModel();
            if ($user->login(true)) {
                $_SESSION['success'] = 'Вы успешно авторизованы';
                redirect(ADMIN);
            } else {
                $_SESSION['error'] = 'Логин/пароль введены неверно';
                redirect();
            }
            // if (UserModel::isAdmin()) {
            // } else {
            // }
        }
        $this->layout = 'login';
    }
}
