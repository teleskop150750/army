<?php

use core\Router;

// пользовательские маршруты
Router::addRoute('^product/(?P<alias>[a-z0-9-]+)/?$', [
    'controller' => 'Product',
    'action' => 'view',
]);

Router::addRoute('^category/(?P<alias>[a-z0-9-]+)/?$', [
    'controller' => 'Category',
    'action' => 'view',
]);

// маршруты по умолчанию
Router::addRoute('^admin$', [
    'controller' => 'Main',
    'action' => 'index',
    'prefix' => 'admin',
]);

Router::addRoute('^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', [
    'prefix' => 'admin',
]);

Router::addRoute('^$', [
    'controller' => 'Main',
    'action' => 'index',
]);

Router::addRoute('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
