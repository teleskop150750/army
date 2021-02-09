<?php

namespace app\models\admin;

use RedBeanPHP\R;

class FilterGroupModel extends AdminModel
{
    public array $attributes = [
        'title' => '',
    ];

    public array $rules = [
        'required' => [
            ['title'],
        ],
    ];

    public function getAttributeGroups(): array
    {
        return R::findAll('attribute_group');
    }

    public function getCountAttributes(int $id): int
    {
        return R::count('attribute_value', 'attr_group_id = ?', [$id]);
    }

    public function deleteAttributeGroup(int $id)
    {
        return R::exec('DELETE FROM attribute_group WHERE id = ?', [$id]);
    }

    /**
     * получить группу
     * @param int $id id группы
     * @return object группа
     */
    public function getAttributeGroup(int $id): object
    {
        return R::load('attribute_group', $id);
    }
}
