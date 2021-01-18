<?php


namespace app\controllers\admin;


use app\models\UserModel;

class UserController extends AppController
{
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