<?php

namespace app\widgets\currency;

use core\exceptions\ParameterException;
use core\App;

class CurrencyWidget
{
    /** @var string путь до шаблона */
    private string $tpl;
    /** @var array валюты */
    private array $currencies;
    /** @var array текущая валюта */
    private array $currency;

    /**
     * CurrencyWidget constructor.
     * @param array $options
     * @throws ParameterException
     */
    public function __construct(array $options = [])
    {
        $this->tpl = __DIR__ . '/currency_tpl/currency.php';
        $this->setOptions($options);
        $this->run();
    }

    /**
     * задать параметры
     * @param $options
     */
    protected function setOptions($options): void
    {
        foreach ($options as $option => $value) {
            if (property_exists($this, $option)) {
                $this->$option = $value;
            }
        }
    }

    /**
     * @throws ParameterException
     */
    protected function run(): void
    {
        $this->currencies = App::$app->getProperty('currencies');
        $this->currency = App::$app->getProperty('currency');
        echo $this->getHtml();
    }

    /**
     * сформировать HTML
     * @return false|string HTML
     */
    protected function getHtml()
    {
        ob_start();
        require_once $this->tpl;
        return ob_get_clean();
    }
}
