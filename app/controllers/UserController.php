<?php

namespace app\controllers;

use app\models\UserModel;
use core\App;

class UserController extends AppController
{
    public function signupAction(): void
    {
        if (!empty($_POST)) {
            /** @var object $user_modal */
            $user_modal = new UserModel();
            $data = $_POST;
            $user_modal->load($data);

            // данные невалидны или пользователь уже не уникален?
            if (!$user_modal->validate($data) || !$user_modal->checkUnique()) {
                $user_modal->getErrors();
                $_SESSION['form_data'] = $data;
            } else {
                $user_modal->attributes['password'] = password_hash(
                    $user_modal->attributes['password'],
                    PASSWORD_DEFAULT
                );

                // пользователь создан?
                if ($id = $user_modal->save('user')) {
//                    debug($user_modal->attributes, '34', 1);
                    foreach ($user_modal->attributes as $k => $v) {
                        if ($k !== 'password') {
                            $_SESSION['user'][$k] = $v;
                        }
                        $_SESSION['user']['id'] = $id;
                    }
                    $_SESSION['signup'] = 'Пользователь зарегистрирован';
                } else {
                    $_SESSION['signup-error'] = 'Ошибка!';
                }
            }
            redirect();
        }
        $this->setMeta('Регистрация');
    }

    public function loginAction(): void
    {
        if (!empty($_POST)) {
            $user = new UserModel();

            // пользователь авторизован
            if ($user->login()) {
                $_SESSION['signup'] = 'Вы успешно авторизованы';
            } else {
                $_SESSION['signup-error'] = 'Логин/пароль введены неверно';
            }
            redirect();
        }
        $this->setMeta('Вход');
    }

    public function logoutAction(): void
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        redirect();
    }

    public function viewAction(): void
    {
        if (!isset($_SESSION['user'])) {
            redirect(PATH);
        }
    }

    public function addAvatarAction(): void
    {
        if (isset($_GET['upload'])) {
            $length = App::$app->getProperty('img-avatar_width');
            $name = $_POST['name'];
            $user_model = new UserModel();
            $user_model->uploadImg($name, $length);
        }
    }
}
