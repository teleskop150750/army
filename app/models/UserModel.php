<?php

namespace app\models;

use RedBeanPHP\R;

class UserModel extends AppModel
{

    public array $attributes = [
        'login' => '',
        'password' => '',
        'name' => '',
        'email' => '',
        'address' => '',
    ];

    public array $rules = [
        'required' => [
            ['login'],
            ['password'],
            ['name'],
            ['email'],
            ['address'],
        ],
        'email' => [
            ['email'],
        ],
        'lengthMin' => [
            ['password', 6],
        ]
    ];

    /**
     * зарегестрирован?
     * @return bool
     */
    public static function checkAuth(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * проверить на уеикальность
     * @return bool
     */
    public function checkUnique(): bool
    {
        /** @var object $user */
        $user = R::findOne('user', 'login = ? OR email = ?', [$this->attributes['login'], $this->attributes['email']]);
        if ($user) {
            if ($user->login === $this->attributes['login']) {
                $this->errors['unique'][] = 'Этот логин уже занят';
            }
            if ($user->email === $this->attributes['email']) {
                $this->errors['unique'][] = 'Этот email уже занят';
            }
            return false;
        }
        return true;
    }

    public function login($isAdmin = false): bool
    {
        $login = !empty(trim($_POST['login'])) ? trim($_POST['login']) : null;
        $password = !empty(trim($_POST['password'])) ? trim($_POST['password']) : null;
        /** @var object $user пользователь*/
        $user = null;
        // логин и пароль переданы?
        if ($login && $password) {
            // это админ?
            if ($isAdmin) {
                $user = R::findOne('user', "login = ? AND role = 'admin'", [$login]);
            } else {
                $user = R::findOne('user', "login = ?", [$login]);
            }
            
            // пользователь существует и пароль подходит?
            if ($user && password_verify($password, $user->password)) {
                foreach ($user as $k => $v) {
                    if ($k !== 'password') {
                        $_SESSION['user'][$k] = $v;
                    }
                }
                return true;
            }
        }
        return false;
    }


    public static function isAdmin(): bool
    {
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin');
    }
}
