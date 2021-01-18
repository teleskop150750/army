<?php

/**
 * дебаг
 * @param mixed $data данные
 * @param string|int $title заголовок
 * @param bool $die
 */
function debug($data = '', string $title = '', $die = false): void
{
    echo "<mark>{$title}</mark>";
    echo '<pre>' . print_r($data, true) . '</pre>';
    if ($die) {
        die;
    }
}

/**
 * редирект
 * @param bool|string $http адрес
 */
function redirect($http = false): void
{
    // передан адрес?
    if ($http) {
        $redirect = $http;
    } else {
        $redirect = $_SERVER['HTTP_REFERER'] ?? PATH;
    }

    header("Location: $redirect");
    exit;
}

/**
 * htmlSpecialChars
 * @param string $str текст
 * @return string
 */
function h(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES);
}
