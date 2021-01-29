<?php

namespace app\controllers;

use app\controllers\base\AppBaseController;
use app\models\AppModel;
use app\widgets\currency\CurrencyWidgetModel;
use core\App;
use core\Cache;
use RedBeanPHP\R;

class AppController extends AppBaseController
{
    /**
     * AppController constructor.
     * @param array $route
     */
    public function __construct(array $route)
    {
        parent::__construct($route);
        new AppModel();
        App::$app->setProperty('currencies', CurrencyWidgetModel::getCurrencies());
        App::$app->setProperty('currency', CurrencyWidgetModel::getCurrency(App::$app->getProperty('currencies')));
        App::$app->setProperty('cats', self::getCacheCategory());
    }

    /**
     * получить кэш категорий
     * @return array категории
     */
    public static function getCacheCategory(): array
    {
        /** @var Cache $cache */
        $cache = Cache::getInstance();
        $cats = $cache->get('cats');

        // категорий нет?
        if (!$cats) {
            $cats = R::getAssoc("SELECT * FROM category");
            $cache->set('cats', $cats);
        }
        return $cats;
    }
}
