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
     * загрузить данные для проверки
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
     * сохранить данные
     * @param string $table таблица
     * @param bool $valid
     * @return int|string id созданной записи
     * @throws SQLAlias
     */
    public function save(string $table, bool $valid = true)
    {
        if ($valid) {
            $tbl = R::dispense($table);
        } else {
            $tbl = R::xdispense($table);
        }

        foreach ($this->attributes as $name => $value) {
            $tbl->$name = $value;
        }
        return R::store($tbl);
    }

    /**
     * обновить запись
     * @param string $table таблица
     * @param int $id id записи
     * @return int|string id измененной записи
     * @throws SQLAlias
     */
    public function update(string $table, int $id)
    {
        $bean = R::load($table, $id);

        foreach ($this->attributes as $name => $value) {
            $bean->$name = $value;
        }

        return R::store($bean);
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
