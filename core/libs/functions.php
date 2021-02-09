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

/**
 * @param string $dateTime
 * @return string
 */
function getArticleDateTime(string $dateTime): string
{
    return str_replace(' ', 'T', $dateTime) . '+03:00';
}

function getCommentDate(string $dateTime): string
{
    $date = explode(' ', $dateTime);
    [$year, $month, $day] = explode('-', $date[0], 3);
    return "{$year}.{$month}.{$day}";
}

function getArticleDate(string $dateTime): string
{
    $date = explode(' ', $dateTime);
    [$year, $month, $day] = explode('-', $date[0], 3);
    switch ($month) {
        case '01':
            $month = 'января';
            break;
        case '02':
            $month = 'февраля';
            break;
        case '03':
            $month = 'марта';
            break;
        case '04':
            $month = 'апреля';
            break;
        case '05':
            $month = 'мая';
            break;
        case '06':
            $month = 'июня';
            break;
        case '07':
            $month = 'июля';
            break;
        case '08':
            $month = 'августа';
            break;
        case '09':
            $month = 'сентября';
            break;
        case '10':
            $month = 'октября';
            break;
        case '11':
            $month = 'ноября';
            break;
        case '12':
            $month = 'декабря';
            break;
        default:
            ;
    }
    return "{$day} {$month} {$year}";
}
