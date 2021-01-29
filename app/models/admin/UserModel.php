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
        'name' => '',
        'email' => '',
        'address' => '',
        'role' => '',
    ];

    public array $rules = [
        'required' => [
            ['login'],
            ['name'],
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
     * получить заказы пользователя
     * @param int $user_id id пользователя
     * @return array|null заказы
     */
    public function getOrders(int $user_id): ?array
    {
        return R::getAll("
            SELECT `order`.`id`, 
                   `order`.`user_id`, 
                   `order`.`status`, 
                   `order`.`date`, 
                   `order`.`update_at`, 
                   `order`.`currency`,
                   ROUND(SUM(`order_product`.`price`), 2) AS `sum` 
            FROM `order`
            JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
            WHERE user_id = {$user_id} 
            GROUP BY `order`.`id`, `order`.`status`
            ORDER BY `order`.`status`, `order`.`id`");
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
