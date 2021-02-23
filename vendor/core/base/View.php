<?php

namespace core\base;

use core\exceptions\ViewException;

class View
{
    /** @var array маршрут */
    protected array $route;
    /** @var string controller */
    protected string $controller;
    /** @var string model */
    protected string $model;
    /** @var string вид */
    protected string $view;
    /** @var string prefix */
    protected string $prefix;
    /** @var bool|string шаблон */
    protected $layout;
    /** @var array Meta */
    protected array $meta = [
        'title' => '',
        'description' => '',
        'keywords' => '',
    ];

    /**
     * View constructor.
     * @param array $route маршрут
     * @param string $view вид
     * @param bool|string $layout шаблон
     * @param array $meta мета
     */
    public function __construct(array $route, string $view = '', $layout = LAYOUT, array $meta = [
        'title' => '',
        'description' => '',
        'keywords' => '',
    ])
    {
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $view;
        $this->prefix = $route['prefix'];
        $this->layout = $layout;
        $this->meta = $meta;
    }

    /**
     * рендер
     * @param array $data данные
     * @throws ViewException
     */
    public function render(array $data = []): void
    {
        extract($data, EXTR_OVERWRITE);
        $this->prefix = str_replace('\\', '/', $this->prefix);
        $viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";

        // вид существует?
        if (is_file($viewFile)) {
            // шаблон отключен?
            if ($this->layout === false) {
                require_once $viewFile;
            } else {
                ob_start();
                require_once $viewFile;
                $content = ob_get_clean();
                $layoutFile = APP . "/views/layouts/{$this->layout}.php";

                //  шаблон существует?
                if (is_file($layoutFile)) {
                    require_once $layoutFile;
                } else {
                    throw new ViewException("Не найден шаблон {$layoutFile}", 500);
                }
            }
        } else {
            throw new ViewException("Не найден вид {$viewFile}", 500);
        }
    }

    /**
     * получить метаданные
     * @return string метаданные
     */
    public function getMeta(): string
    {
        $meta = '<title>' . $this->meta['title'] . '</title>' . PHP_EOL;
        $meta .= '<meta name="description" content="' . $this->meta['description'] . '">' . PHP_EOL;
        $meta .= '<meta name="keywords" content="' . $this->meta['keywords'] . '">' . PHP_EOL;
        return $meta;
    }
}
