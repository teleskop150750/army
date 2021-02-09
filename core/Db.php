<?php

namespace core;

use core\exceptions\RBException;
use RedBeanPHP\R;
use RedBeanPHP\RedException;

class Db
{
    use TSingleton;

    /**
     * Db constructor.
     * @throws RBException|RedException
     */
    protected function __construct()
    {
        $db = require CONF . '/config_db.php';
        R::setup($db['dsn'], $db['user'], $db['pass']);

        // нет соединения с БД
        if (!R::testConnection()) {
            throw new RBException('Нет соединения с БД', 500);
        }

        R::freeze(true);

        if (DEBUG) {
            R::debug(true, 1);
        }

        R::ext('xdispense', function ($type) {
            return R::getRedBean()->dispense($type);
        });
    }
}
