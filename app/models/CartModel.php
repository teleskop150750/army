<?php

namespace app\models;

use core\App;
use core\exceptions\ParameterException;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class CartModel extends AppModel
{
    /**
     * получить продукт
     * @param int $id id продукта
     * @return object|null продукт
     */
    public function getProduct(int $id): ?object
    {
        return R::findOne('product', 'id = ?', [$id]);
    }

    /**
     * получить модификацию
     * @param int $mod_id id модификации
     * @param int $product_id id продукта
     * @return object|null модификация
     */
    public function getModification(int $mod_id, int $product_id): ?object
    {
        return R::findOne('modification', 'id = ? AND product_id = ?', [$mod_id, $product_id]);
    }

    /**
     * добавить в козину
     * @param object $product продукт
     * @param int $qty количество
     * @param null $mod модификация
     * @throws ParameterException
     */
    public function addToCart(object $product, int $qty = 1, $mod = null): void
    {
        if (!isset($_SESSION['cart.currency'])) {
            $_SESSION['cart.currency'] = App::$app->getProperty('currency');
        }

        if ($mod) {
            $id = "{$product->id}-{$mod->id}";
            $title = "{$product->title} ({$mod->title})";
            $price = $mod->price;
        } else {
            $id = $product->id;
            $title = $product->title;
            $price = $product->price;
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$id] = [
                'qty' => $qty,
                'title' => $title,
                'alias' => $product->alias,
                'price' => $price * $_SESSION['cart.currency']['value'],
                'img' => $product->img,
            ];
        }

        $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum'])
            ? $_SESSION['cart.sum'] + $qty * ($price * $_SESSION['cart.currency']['value'])
            : $qty * ($price * $_SESSION['cart.currency']['value']);
    }

    /**
     * удалить из корзины
     * @param int $id id товара
     */
    public function deleteItem(int $id): void
    {
        $qtyMinus = $_SESSION['cart'][$id]['qty'];
        $sumMinus = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];
        $_SESSION['cart.qty'] -= $qtyMinus;
        $_SESSION['cart.sum'] -= $sumMinus;
        unset($_SESSION['cart'][$id]);
    }
}
