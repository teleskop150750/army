<?php


namespace app\models\admin;

use RedBeanPHP\R;

class FilterAttrModel extends AdminModel
{
    public array $attributes = [
        'value' => '',
        'attr_group_id' => '',
    ];

    public array $rules = [
        'required' => [
            ['value'],
            ['attr_group_id'],
        ],
        'integer' => [
            ['attr_group_id'],
        ],
    ];

    public function getAttrs(): ?array
    {
        return R::getAssoc("
            SELECT attribute_value.*, 
                   attribute_group.title 
            FROM attribute_value 
                JOIN attribute_group ON attribute_group.id = attribute_value.attr_group_id
        ");
    }

    public function getGroups(): array
    {
        return R::findAll('attribute_group');
    }

    public function getAttr(int $id): object
    {
        return R::load('attribute_value', $id);
    }

    public function deleteAttrs(int $id): void
    {
        R::exec("DELETE FROM attribute_product WHERE attr_id = ?", [$id]);
        R::exec("DELETE FROM attribute_value WHERE id = ?", [$id]);
    }
}
