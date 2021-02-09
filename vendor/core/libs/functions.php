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
 * @param mixed $str текст
 * @return string
 */
function h($str): string
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function getArticleDateTime(string $date)
{
    return str_replace(' ', 'T', $date);
}

function getArticleDate(string $date)
{
   $date = explode(' ', $date);
    [$y, $m, $d] = explode('-', $date[0]);
    switch ($m) {
        case '01':
            $m = 'января';
            break;
        case '02':
            $m = 'января';
            break;
        case '03':
            $m = 'марта';
            break;
        case '04':
            $m = 'апреля';
            break;
        case '05':
            $m = 'мая';
            break;
        case '06':
            $m = 'июня';
            break;
        case '07':
            $m = 'июля';
            break;
        case '08':
            $m = 'августа';
            break;
        case '09':
            $m = 'сентября';
            break;
        case '10':
            $m = 'октября';
            break;
        case '11':
            $m = 'ноября';
            break;
        case '12':
            $m = 'декабря';
            break;
    }
    return "$d $m $y";
}
