<?php

namespace core;

use core\exceptions\ParameterException;

/**
 * Class Реестр
 * @package core
 */
class Registry
{
    use TSingleton;

    /** @var array параметры */
    public static array $properties = [];

    /**
     * записать параметр
     * @param string $name название параметра
     * @param mixed $parameter параметр
     */
    public function setProperty(string $name, $parameter): void
    {
        self::$properties[$name] = $parameter;
    }

    /**
     * получить параметр
     * @param string $name название параметра
     * @return mixed параметр
     * @throws ParameterException
     */
    public function getProperty(string $name)
    {
        // существует такой параметр?
        if (isset(self::$properties[$name])) {
            debug($this->getProperties());
            debug($name);
            debug(self::$properties[$name]);
            return self::$properties[$name];
        }
        debug($this->getProperties());
        debug($name, '', 1);
        die();
        throw new ParameterException('Дан неверный ключ');
    }

    /**
     * получить все параметры
     * @return array параметры
     */
    public function getProperties(): array
    {
        return self::$properties;
    }
}
