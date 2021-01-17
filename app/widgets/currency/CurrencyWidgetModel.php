<?php

namespace app\widgets\currency;

use app\models\AppModel;
use RedBeanPHP\R;

class CurrencyWidgetModel extends AppModel
{
    /**
     * получить список валют
     * @return array валюты
     */
    public static function getCurrencies(): array
    {
        return R::getAssoc("SELECT code, title, symbol_left, symbol_right, value, base FROM currency ORDER BY base DESC");
    }

    /**
     * получить текущую валюту
     * @param array $currencies валюты
     * @return array валюта
     */
    public static function getCurrency(array $currencies): array
    {
        // есть в куках и в валютах?
        if (isset($_COOKIE['currency']) && array_key_exists($_COOKIE['currency'], $currencies)) {
            $key = $_COOKIE['currency'];
        } else {
            $key = key($currencies);
        }

        $currency = $currencies[$key];
        $currency['code'] = $key;
        return $currency;
    }
}
