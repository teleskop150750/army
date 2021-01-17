<?php

/**
 * дебаг
 * @param mixed $data данные
 * @param string|int $title заголовок
 */
function debug($data = '', string $title = ''): void
{
    echo "<mark>{$title}</mark>";
    echo '<pre>';
    print_r($data);
    echo '</pre>';
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
 * htmlspecialchars
 * @param string $str текст
 * @return string
 */
function h(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES);
}
