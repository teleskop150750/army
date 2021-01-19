<?php

namespace core;

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
            return self::$properties[$name];
        }

        return null;
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
