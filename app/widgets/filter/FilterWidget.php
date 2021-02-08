<?php

namespace app\widgets\filter;

use RedBeanPHP\R;
use core\Cache;

class FilterWidget
{
    /** @var array|null группы гатегорий*/
    public ?array $groups;
    public ?array $attrs;
    public string $tpl;
    public ?array $filter;

    public function __construct($filter = null, $tpl = '')
    {
        $this->filter = $filter;
        $this->tpl = $tpl ?: WIDGETS . '/filter/filter_tpl/filter.php';
        $this->run();
    }

    protected function run(): void
    {
        $this->groups = $this->getGroups();
        $this->attrs = self::getAttrs();
        $filters = $this->getHtml();
        echo $filters;
    }

    protected function getHtml()
    {
        ob_start();
        $filter = self::getFilter();
        if (!empty($filter)) {
            $filter = explode(',', $filter);
        }
        require $this->tpl;
        return ob_get_clean();
    }

    /**
     * получить группы
     * @return array|null
     */
    protected function getGroups(): ?array
    {
        $cache = Cache::getInstance();
        $groups = $cache->get('filter_group');
        if (!$groups) {
            $groups = R::getAssoc('SELECT id, title FROM attribute_group');
            $cache->set('filter_group', $groups);
        }
        return $groups;
    }

    /**
     * получить атрибуты
     * @return array
     */
    protected static function getAttrs(): array
    {
//        {
//            [1] => Array
//            (
//                [1] => Механика с автоподзаводом
//                [2] => Механика с автоподзаводом
//            )
//            [2] => Array
//            (
//                [3] => Сапфировое
//                [4] => Сапфировое
//            )
//        }
        $cache = Cache::getInstance();
        $attrs = $cache->get('filter_attrs');
        if (!$attrs) {
            $data = R::getAssoc('SELECT * FROM attribute_value');
            $attrs = [];
            foreach ($data as $key => $value) {
                $attrs[$value['attr_group_id']][$key] = $value['value'];
            }
            $cache->set('filter_attrs', $attrs);
        }
        return $attrs;
    }


    /**
     * получить фильтры из запроса
     * @return string|null
     */
    public static function getFilter(): ?string
    {
        $filter = null;
        if (!empty($_GET['filter'])) {
            $filter = preg_replace("#[^\d,]+#", '', $_GET['filter']);
            $filter = trim($filter, ',');
        }
        return $filter;
    }

    /**
     * получить количество групп
     * @param $filter
     * @return int
     */
    public static function getCountGroups($filter): int
    {
        $filters = explode(',', $filter);
        $attrs = self::getAttrs();
        $data = [];
        foreach ($attrs as $key => $item) {
            foreach ($item as $index => $value) {
                if (in_array($index, $filters, false)) {
                    $data[] = $key;
                    break;
                }
            }
        }
        return count($data);
    }
}
