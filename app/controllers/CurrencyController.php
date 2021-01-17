<?php

namespace app\controllers;

use app\models\CurrencyModel;

class CurrencyController extends AppController
{
    public function changeAction(): void
    {
        $c_modal = new CurrencyModel();
        /** @var string код валюты */
        $currencyCode = $_GET['curr'] ?? null;

        // код валюты передан?
        if ($currencyCode) {
            /** @var object валюта */
            $currency = $c_modal->getCurrency($currencyCode);

            // валюта получена
            if ($currency !== null) {
                setcookie('currency', $currencyCode, time() + 3600 * 24 * 7, '/');
                self::reCalc($currency);
            }
        }
        redirect();
    }
    /**
     * перерасчитать цену
     * @param object $curr
     */
    public static function reCalc(object $curr): void
    {
        if (isset($_SESSION['cart.currency'])) {
            if ($_SESSION['cart.currency']['base']) {
                $_SESSION['cart.sum'] *= $curr->value;
            } else {
                $_SESSION['cart.sum'] = $_SESSION['cart.sum'] / $_SESSION['cart.currency']['value'] * $curr->value;
            }

            foreach ($_SESSION['cart'] as $id => $product) {
                if ($_SESSION['cart.currency']['base']) {
                    $_SESSION['cart'][$id]['price'] *= $curr->value;
                } else {
                    $_SESSION['cart'][$id]['price'] = $_SESSION['cart'][$id]['price']
                        / $_SESSION['cart.currency']['value']
                        * $curr->value;
                }
            }

            foreach ($curr as $id => $product) {
                $_SESSION['cart.currency'][$id] = $product;
            }
        }
    }
}
