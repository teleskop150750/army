<?php


namespace app\models\base;

use RedBeanPHP\R;

class UserBaseBaseModel extends AppBaseModel
{
    public array $attributes = [];

    public array $rules = [];


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

    /**
     * проверить на админа
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin');
    }
}
