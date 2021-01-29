<?php

namespace app\widgets\menu;

use core\exceptions\ParameterException;
use core\App;
use core\Cache;
use RedBeanPHP\R;

class MenuWidget
{
    /** @var array|null массив категорий  */
    protected ?array $data = [];
    /** @var array дерево меню */
    protected array $tree = [];
    /** @var string HTML */
    protected string $menuHtml = '';
    /** @var string путь к шаблону */
    protected string $tpl = WIDGETS . '/menu_tpl/menu.php';
    /** @var string контейнер */
    protected string $container = 'ul';
    /** @var string класс css */
    protected string $class = 'menu';
    /** @var string таблица БД */
    protected string $table = 'category';
    /** @var int время кэширования */
    protected int $cache = 3600;
    /** @var string ключ кэша */
    protected string $cacheKey = 'site_menu';
    /** @var array html атрибуты */
    protected array $attrs = [];
    /** @var string перед меню */
    protected string $prepend = '';

    public function __construct($options = [])
    {
        $this->setOptions($options);
        $this->run();
    }

    /**
     * задать параметры
     * @param $options
     */
    protected function setOptions($options): void
    {
        foreach ($options as $option => $value) {
            if (property_exists($this, $option)) {
                $this->$option = $value;
            }
        }
    }

    /**
     * @throws ParameterException
     */
    protected function run(): void
    {
        $cache = Cache::getInstance();
        $this->menuHtml = $cache->get($this->cacheKey);

        // нет закэшированного меню?
        if (!$this->menuHtml) {
            $this->data = App::$app->getProperty('cats');

            // нет категоий в свойствах?
            if (!$this->data) {
                $this->data = R::getAssoc("SELECT * FROM {$this->table}");
            }

            $this->tree = $this->getTree();
            $this->menuHtml = $this->getMenuHtml($this->tree);

            if ($this->cache) {
                $cache->set($this->cacheKey, $this->menuHtml, $this->cache);
            }
        }
        $this->output();
    }

    /**
     * получить дерево меню
     * @return array дереао
     */
    protected function getTree(): array
    {
        $tree = [];
        $data = $this->data;
        foreach ($data as $id => &$node) {
            if (!$node['parent_id']) {
                $tree[$id] =& $node;
            } else {
                $data[$node['parent_id']]['children'][$id] =& $node;
            }
        }
        unset($node);
        return $tree;
    }

    /**
     * получить HTML
     * @param array $tree дерево
     * @param string $tab табуляция
     * @return string HTML
     */
    protected function getMenuHtml(array $tree, string $tab = ''): string
    {
        $str = '';
        foreach ($tree as $id => $category) {
            $str .= $this->catToTemplate($category, $tab, $id);
        }
        return $str;
    }

    /**
     * категорию в шаблон
     * @param array $category категория
     * @param string $tab табуляция
     * @param int $id id
     * @return false|string
     */
    protected function catToTemplate(array $category, string $tab, int $id)
    {
        ob_start();
        require $this->tpl;
        return ob_get_clean();
    }

    /**
     * вывод
     */
    protected function output(): void
    {
        $attrs = '';
        if (!empty($this->attrs)) {
            foreach ($this->attrs as $k => $v) {
                $attrs .= " $k='$v' ";
            }
        }
        echo "<{$this->container} class='{$this->class}' $attrs>";
        echo $this->prepend;
        echo $this->menuHtml;
        echo "</{$this->container}>";
    }
}
