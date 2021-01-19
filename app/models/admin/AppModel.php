<?php

namespace app\models\admin;

use core\base\Model;
use RedBeanPHP\R;

class AppModel extends Model
{
    /**
     * создать Alias
     * @param string $table таблица
     * @param string $field поле
     * @param string $str строка
     * @param int $id id
     * @return string alias
     */
    public static function createAlias(string $table, string $field, string $str, int $id): string
    {
        $str = self::str2url($str);
        $res = R::findOne($table, "$field = ?", [$str]);

        // alias уже существует?
        if ($res) {
            $str = "{$str}-{$id}";
            $res = R::count($table, "$field = ?", [$str]);

            // alias уже существует?
            if ($res) {
                $str = self::createAlias($table, $field, $str, $id);
            }
        }
        return $str;
    }

    /**
     * преобразовать строку в url
     * @param string $str строка
     * @return string url
     */
    public static function str2url(string $str): string
    {
        // переводим в транслит
        $str = self::rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        return $str;
    }

    /**
     * переводим в транслит
     * @param string $string
     * @return string
     */
    public static function rus2translit(string $string): string
    {
        $converter = array(

            'а' => 'a', 'б' => 'b', 'в' => 'v',

            'г' => 'g', 'д' => 'd', 'е' => 'e',

            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',

            'и' => 'i', 'й' => 'y', 'к' => 'k',

            'л' => 'l', 'м' => 'm', 'н' => 'n',

            'о' => 'o', 'п' => 'p', 'р' => 'r',

            'с' => 's', 'т' => 't', 'у' => 'u',

            'ф' => 'f', 'х' => 'h', 'ц' => 'c',

            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',

            'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',

            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',


            'А' => 'A', 'Б' => 'B', 'В' => 'V',

            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',

            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',

            'И' => 'I', 'Й' => 'Y', 'К' => 'K',

            'Л' => 'L', 'М' => 'M', 'Н' => 'N',

            'О' => 'O', 'П' => 'P', 'Р' => 'R',

            'С' => 'S', 'Т' => 'T', 'У' => 'U',

            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',

            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',

            'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',

            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',

        );

        return strtr($string, $converter);
    }
}
