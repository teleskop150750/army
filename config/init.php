<?php

define("DEBUG", 1);
define("ROOT", dirname(__DIR__));
define("WWW", ROOT . '/public');
define("APP", ROOT . '/app');
define("CORE", ROOT . '/vendor/core');
define("LIBS", ROOT . '/vendor/core/libs');
define("CACHE", ROOT . '/tmp/cache');
define("CONF", ROOT . '/config');
define("WIDGETS", APP . '/widgets');
define("LAYOUT", 'default');

/** @var string протокол */
$protocol = stripos($_SERVER["SERVER_PROTOCOL"], 'https') === 0 ? 'https' : 'http';
if ($_SERVER["SERVER_PORT"] === 443) {
    $protocol = 'https';
} elseif (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] === 'on') || ($_SERVER['HTTPS'] === '1'))) {
    $protocol = 'https';
}

// http://site/public/index.php
$app_path = "{$protocol}://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
// http://site/public/
$app_path = preg_replace("#[^/]+$#", '', $app_path);
// http://site
$app_path = str_replace('/public/', '', $app_path);
define("PATH", $app_path);
define("ADMIN", PATH . '/admin');

require_once ROOT . '/vendor/autoload.php';
