<?php

namespace core;

class App
{
    /** @var Registry обект реестра */
    public static Registry $app;

    /**
     * App constructor.
     * @throws exceptions\ClassException
     * @throws exceptions\MethodException
     * @throws exceptions\RouteException
     */
    public function __construct()
    {
        $query = trim($_SERVER['QUERY_STRING'], '/');
        session_start();
        new ErrorHandler();
        self::$app = Registry::getInstance();
        $this->setParams();
        Router::dispatch($query);
    }

    /**
     * задать параметры
     */
    protected function setParams(): void
    {
        $params = require CONF . '/params.php';

        // парамтеры не пустые?
        if (!empty($params)) {
            foreach ($params as $key => $parameter) {
                self::$app->setProperty($key, $parameter);
            }
        }
    }
}
