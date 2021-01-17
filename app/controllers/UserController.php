<?php

namespace app\controllers;

use app\models\UserModel;

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
                if ($user_modal->save('user')) {
                    $_SESSION['success'] = 'Пользователь зарегистрирован';
                } else {
                    $_SESSION['error'] = 'Ошибка!';
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
                $_SESSION['success'] = 'Вы успешно авторизованы';
            } else {
                $_SESSION['error'] = 'Логин/пароль введены неверно';
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
}
