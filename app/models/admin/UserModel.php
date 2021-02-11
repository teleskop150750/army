<?php

namespace app\models\admin;

use app\models\base\UserBaseModel;
use RedBeanPHP\R;

class UserModel extends UserBaseModel
{
    public array $attributes = [
        'id' => '',
        'login' => '',
        'password' => '',
        'email' => '',
        'img' => 'avatar-default.jpg',
        'role' => '',
    ];

    public array $rules = [
        'required' => [
            ['login'],
            ['email'],
            ['role'],
        ],
        'email' => [
            ['email'],
        ],
    ];

    /**
     * проверить на уникальность
     * @return bool
     */
    public function checkUnique(): bool
    {
        /** @var object $user */
        $user = R::findOne(
            'user',
            '(login = ? OR email = ?) AND id <> ?',
            [$this->attributes['login'], $this->attributes['email'], $this->attributes['id']]
        );

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

    /**
     * получить количество пользователей
     * @return int количество пользователей
     */
    public function getTotal(): int
    {
        return R::count('user');
    }

    /**
     * получить пользоватедй
     * @param int $start страница
     * @param int $perPage количество на стринице
     * @return array пользователи
     */
    public function getUsers(int $start, int $perPage): array
    {
        return R::findAll('user', "LIMIT $start, $perPage");
    }

    /**
     * получить пользователя
     * @param int $user_id id пользователя
     * @return object пользователь
     */
    public function getUser(int $user_id): object
    {
        return R::load('user', $user_id);
    }

    /**
     * проверить на админа
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin');
    }
}
