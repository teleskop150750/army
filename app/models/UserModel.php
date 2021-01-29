<?php

namespace app\models;

use app\models\base\UserBaseModel;
use RedBeanPHP\R;

class UserModel extends UserBaseModel
{

    public array $attributes = [
        'login' => '',
        'password' => '',
        'name' => '',
        'email' => '',
        'address' => '',
        'role' => 'user',
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
     * проверить на уникальность
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
}
