<?php

namespace app\models;

use RedBeanPHP\R;

class CurrencyModel
{
    /**
     * получить валюту
     * @param string $currencyCode код валюты
     * @return null|object валюта
     */
    public function getCurrency(string $currencyCode): ?object
    {
        return R::findOne('currency', 'code = ?', [$currencyCode]);
    }
}
