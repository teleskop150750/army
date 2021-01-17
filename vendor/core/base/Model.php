<?php

namespace core\base;

use RedBeanPHP\R;
use core\Db;
use RedBeanPHP\RedException\SQL as SQLAlias;
use Valitron\Validator;

abstract class Model
{
    public array $attributes = [];
    public array $errors = [];
    public array $rules = [];

    public function __construct()
    {
        Db::getInstance();
    }

    /**
     * загрузить
     * @param $data
     */
    public function load(array $data): void
    {
        foreach ($this->attributes as $name => $value) {
            if (isset($data[$name])) {
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validate(array $data): bool
    {
        Validator::langDir(WWW . '/validator/lang');
        Validator::lang('ru');
        $v = new Validator($data);
        $v->rules($this->rules);
        if ($v->validate()) {
            return true;
        }
        $this->errors = $v->errors();
        return false;
    }

    /**
     * создать пользователя
     * @param string $table
     * @return int|string
     * @throws SQLAlias
     */
    public function save(string $table)
    {
        $tbl = R::dispense($table);
        foreach ($this->attributes as $name => $value) {
            $tbl->$name = $value;
        }
        return R::store($tbl);
    }


    /**
     * получить ошибки
     */
    public function getErrors(): void
    {
        $errors = '<ul>';
        foreach ($this->errors as $error) {
            foreach ($error as $item) {
                $errors .= "<li>$item</li>";
            }
        }
        $errors .= '</ul>';
        $_SESSION['error'] = $errors;
    }
}
