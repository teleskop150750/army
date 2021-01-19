<?php

namespace core;

use app\controllers\AppController;
use core\exceptions\ClassException;
use core\exceptions\MethodException;
use core\exceptions\RouteException;

/**
 * Class Маршрутизатор
 * @package core
 */
class Router
{
    /** @var array маршруты */
    private static array $routes = [];

    /** @var array текущий маршрут */
    private static array $route = [];

    /**
     * добавить маршрут
     * @param string $regexp шабдон
     * @param array $route маршрут
     */
    public static function addRoute(string $regexp, array $route = []): void
    {
        self::$routes[$regexp] = $route;
    }

    /**
     * получить маршруты
     * @return array маршруты
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * получить текущий маршрут
     * @return array текущий маршрут
     */
    public static function getRoute(): array
    {
        return self::$route;
    }

    /**
     * перейти по адресу
     * @param string $url адрес
     * @throws RouteException|ClassException|MethodException
     */
    public static function dispatch(string $url): void
    {
        // адрес без GET
        $url = self::removeQueryString($url);
        self::setCurrentRoute($url);
        $controllerName = 'app\controllers\\' . self::$route['prefix'] . self::$route['controller'] . 'Controller';

        // существует такой Controller?
        if (class_exists($controllerName)) {
            /** @var AppController объект контроллера*/
            $controllerObject = new $controllerName(self::$route);
            $action = self::formatAction(self::$route['action']) . 'Action';

            // action существует?
            if (method_exists($controllerObject, $action)) {
                $controllerObject->$action();
                $controllerObject->getView();
            } else {
                throw new MethodException("Метод не найден {$controllerName}::{$action}", 404);
            }
        } else {
            throw new ClassException("Контроллер не найден {$controllerName}", 404);
        }
    }

    /**
     * удалить GET параметры из url
     * @param string $url адрес controller/action?a=1&b=2
     * @return string controller/action
     */
    private static function removeQueryString(string $url): string
    {
        $params = explode('&', $url, 2);

        // это не гланая страница
        if (strpos($params[0], '=') === false) {
            return rtrim($params[0], '/');
        }

        return '';
    }

    /**
     * записать текуший маршрут
     * @param string $url адрес
     * @return void
     * @throws RouteException
     */
    public static function setCurrentRoute(string $url): void
    {
        foreach (self::$routes as $pattern => $route) {
            // соответствует шаблону?
            if (preg_match("#{$pattern}#i", $url, $matches)) {
                foreach ($matches as $key => $value) {
                    // строковый ключ?
                    if (is_string($key)) {
                        $route[$key] = $value;
                    }
                }

                $route['controller'] = self::formatController($route['controller']);

                // action пустой?
                if (empty($route['action'])) {
                    $route['action'] = 'index';
                }

                // prefix не сущействует?
                if (!isset($route['prefix'])) {
                    $route['prefix'] = '';
                } else {
                    $route['prefix'] .= '\\';
                }

                self::$route = $route;
                return;
            }
        }
        throw new RouteException('Не найден маршрут', 404);
    }
    /**
     * CamelCase
     * @param string $controller camel-case
     * @return string CamelCase
     */
    private static function formatController(string $controller): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $controller)));
    }

    /**
     * camelCase
     * @param string $action camel-case
     * @return string camelCase
     */
    private static function formatAction(string $action): string
    {
        return lcfirst(self::formatController($action));
    }
}
