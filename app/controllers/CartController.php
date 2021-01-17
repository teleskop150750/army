<?php

namespace app\controllers;

use app\models\CartModel;
use app\models\OrderModel;
use app\models\UserModel;
use core\exceptions\ParameterException;
use RedBeanPHP\RedException\SQL;

class CartController extends AppController
{
    /**
     * @throws ParameterException
     */
    public function addAction(): void
    {
        $cart_modal = new CartModel();

        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        $qty = !empty($_GET['qty']) ? (int)$_GET['qty'] : 1;
        $mod_id = !empty($_GET['mod']) ? (int)$_GET['mod'] : null;
        $product = null;
        $mod = null;

        if ($id) {
            $product = $cart_modal->getProduct($id);
            if (!$product) {
                return;
            }

            if ($mod_id) {
                $mod = $cart_modal->getModification($mod_id, $id);
            }
        }

        $cart_modal->addToCart($product, $qty, $mod);

        if ($this->isAjax()) {
            $this->layout = false;
            $this->view = 'cart_modal';
        } else {
            redirect();
        }
    }

    public function deleteAction(): void
    {
        $id = $_GET['id'] ?? null;

        if (isset($_SESSION['cart'][$id])) {
            $cart_modal = new CartModel();
            $cart_modal->deleteItem($id);
        }

        if ($this->isAjax()) {
            $this->layout = false;
            $this->view = 'cart_modal';
        } else {
            redirect();
        }
    }

    public function clearAction(): void
    {
        unset(
            $_SESSION['cart'],
            $_SESSION['cart.qty'],
            $_SESSION['cart.sum'],
            $_SESSION['cart.currency']
        );
        if ($this->isAjax()) {
            $this->layout = false;
            $this->view = 'cart_modal';
        } else {
            redirect();
        }
    }

    public function showAction(): void
    {
        if ($this->isAjax()) {
            $this->layout = false;
            $this->view = 'cart_modal';
        }
    }

    public function viewAction(): void
    {
        $this->setMeta('Корзина');
    }

    /**
     * @throws ParameterException
     * @throws SQL
     */
    public function checkoutAction(): void
    {
        if (!empty($_POST)) {
            // пользователь незарегестрирован?
            if (!UserModel::checkAuth()) {
                $user = new UserModel();
                $data = $_POST;
                $user->load($data);
                // данные не валидны и такой пользвователь уже существует?
                if (!$user->validate($user->attributes) || !$user->checkUnique()) {
                    $user->getErrors();
                    $_SESSION['form_data'] = $data;
                    redirect();
                } else {
                    $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                    $user_id = $user->save('user');
                    // пользоваетель не создан?
                    if (!$user_id) {
                        $_SESSION['error'] = 'Ошибка!';
                        redirect();
                    }
                }
            }

            // сохранение заказа
            $data['user_id'] = $user_id ?? $_SESSION['user']['id'];
            $data['note'] = !empty($_POST['note']) ? $_POST['note'] : '';
            $user_email = $_SESSION['user']['email'] ?? $_POST['email'];
            $order_id = OrderModel::saveOrder($data);
            OrderModel::mailOrder($order_id, $user_email);
        }
        redirect();
    }
}
