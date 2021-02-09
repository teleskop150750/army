<?php

namespace app\controllers\admin;

use app\models\admin\FilterAttrModel;
use app\models\admin\FilterGroupModel;
use core\exceptions\IdException;
use RedBeanPHP\RedException\SQL;

class FilterController extends AdminController
{
    public function attributeGroupAction(): void
    {
        $attrs_group_model = new FilterGroupModel();
        $attrs_group = $attrs_group_model->getAttributeGroups();
        $this->setMeta('Группы фильтров');
        $this->setData(compact('attrs_group'));
    }

    /**
     * @throws IdException
     */
    public function groupDeleteAction(): void
    {
        $attrs_group_model = new FilterGroupModel();
        $id = $this->getRequestID();
        $count = $attrs_group_model->getCountAttributes($id);
        if ($count) {
            $_SESSION['error'] = 'Удаление невозможно, в группе есть атрибуты';
            redirect();
        }
        $attrs_group_model->deleteAttributeGroup($id);
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    /**
     * @throws SQL
     */
    public function groupAddAction(): void
    {
        if (!empty($_POST)) {
            $attrs_group_model = new FilterGroupModel();
            $data = $_POST;
            $attrs_group_model->load($data);
            if (!$attrs_group_model->validate($data)) {
                $attrs_group_model->getErrors();
                redirect();
            }
            if ($attrs_group_model->save('attribute_group', false)) {
                $_SESSION['success'] = 'Группа добавлена';
                redirect();
            }
        }
        $this->setMeta('Новая группа фильтров');
    }

    public function groupEditAction(): void
    {
        $attrs_group_model = new FilterGroupModel();
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $data = $_POST;
            $attrs_group_model->load($data);
            if (!$attrs_group_model->validate($data)) {
                $attrs_group_model->getErrors();
                redirect();
            }
            if ($attrs_group_model->update('attribute_group', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = $this->getRequestID();
        $group = $attrs_group_model->getAttributeGroup($id);
        $this->setMeta("Редактирование группы {$group->title}");
        $this->setData(compact('group'));
    }

//    --------------------------------------------------------------------

    public function attributeAction(): void
    {
        $attrs_modal = new FilterAttrModel();
        $attrs = $attrs_modal->getAttrs();
        $this->setMeta('Фильтры');
        $this->setData(compact('attrs'));
    }

    public function attributeAddAction(): void
    {
        $attrs_model = new FilterAttrModel();
        if (!empty($_POST)) {
            $data = $_POST;
            $attrs_model->load($data);
            if (!$attrs_model->validate($data)) {
                $attrs_model->getErrors();
                redirect();
            }
            if ($attrs_model->save('attribute_value', false)) {
                $_SESSION['success'] = 'Атрибут добавлен';
                redirect();
            }
        }
        $group = $attrs_model->getGroups();
        $this->setMeta('Новый фильтр');
        $this->setData(compact('group'));
    }

    /**
     * @throws IdException|SQL
     */
    public function attributeEditAction(): void
    {
        $attrs_model = new FilterAttrModel();
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $data = $_POST;
            $attrs_model->load($data);
            if (!$attrs_model->validate($data)) {
                $attrs_model->getErrors();
                redirect();
            }
            if ($attrs_model->update('attribute_value', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = $this->getRequestID();
        $attr = $attrs_model->getAttr($id);
        $attrs_group = $attrs_model->getGroups();
        $this->setMeta('Редактирование атрибута');
        $this->setData(compact('attr', 'attrs_group'));
    }

    public function attributeDeleteAction(): void
    {
        $attrs_model = new FilterAttrModel();
        $id = $this->getRequestID();
        $attrs_model->deleteAttrs($id);
        $_SESSION['success'] = 'Удалено';
        redirect();
    }
}
