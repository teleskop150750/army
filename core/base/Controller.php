<?php

namespace core\base;

use core\exceptions\ViewException;

abstract class Controller
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
    /** @var string|bool шаблон */
    public $layout = LAYOUT;
    /** @var array данные */
    protected array $data = [];
    /** @var array Meta */
    protected array $meta = [
        'title' => '',
        'description' => '',
        'keywords' => '',
    ];

    public function __construct(array $route)
    {
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $route['action'];
        $this->prefix = $route['prefix'];
    }

    /**
     * получить вид
     * @throws ViewException
     */
    public function getView(): void
    {
        $viewObject = new View($this->route, $this->view, $this->layout, $this->meta);
        $viewObject->render($this->data);
    }

    /**
     * задать данные
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * задать метаданные
     * @param string|null $title
     * @param string|null $description
     * @param string|null $keywords
     */
    public function setMeta(?string $title = '', ?string $description = '', ?string $keywords = ''): void
    {
        $this->meta['title'] = $title;
        $this->meta['description'] = $description;
        $this->meta['keywords'] = $keywords;
    }

    /**
     * проверка на Ajax
     * @return bool
     */
    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function loadView($view, $vars = [])
    {
        extract($vars, EXTR_OVERWRITE);
        ob_start();
        require APP . "/views/{$this->prefix}{$this->controller}/{$view}.php";
        $content = ob_get_clean();
        echo $content;
        die;
    }
}
