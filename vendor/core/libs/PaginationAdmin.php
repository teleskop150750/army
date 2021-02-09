<?php

namespace core\libs;

class PaginationAdmin
{
    /** @var int текущая страница */
    public int $currentPage;
    /** @var int количество на странице */
    public int $perPage;
    /** @var int количество всех записей */
    public int $total;
    /** @var int количество страниц */
    public int $countPages;
    /** @var string параметры*/
    public string $uri;

    public function __construct(int $pageNumber, int $perPage, int $total)
    {
        $this->perPage = $perPage;
        $this->total = $total;
        $this->countPages = $this->getCountPages();
        $this->currentPage = $this->getCurrentPage($pageNumber);
        $this->uri = $this->getParams();
    }

    /**
     * получить количество страниц
     * @return int количество страниц
     */
    public function getCountPages(): int
    {
        return ceil($this->total / $this->perPage) ?: 1;
    }

    /**
     * получить номер текущей страницы
     * @param int $page страница из url
     * @return int номер страницы
     */
    public function getCurrentPage(int $page): int
    {
        if (!$page || $page < 1) {
            return 1;
        }
        if ($page > $this->countPages) {
            return $this->countPages;
        }
        return $page;
    }

    /**
     * получить начало
     * @return int
     */
    public function getStart(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    /**
     * получить параметры
     * @return string параметры
     */
    public function getParams(): string
    {
        $url = $_SERVER['REQUEST_URI'];
        preg_match_all("#filter=[\d,&]#", $url, $matches);

        // параметры дублируются?
        if (count($matches[0]) > 1) {
            $url = preg_replace("#filter=[\d,&]+#", "", $url, 1);
        }

        $url = explode('?', $url);
        $uri = $url[0] . '?';

        // параметры существуют?
        if (isset($url[1]) && $url[1] !== '') {
            $params = explode('&', $url[1]);
            foreach ($params as $param) {
                if (!preg_match("#page=#", $param)) {
                    $uri .= "{$param}&amp;";
                }
            }
        }
        return urldecode($uri);
    }

    public function getHtml()
    {
        /** @var string ссылка НАЗАД */
        $back = null;
        /** @var string ссылка ВПЕРЕД */
        $forward = null;
        /** @var string ссылка В НАЧАЛО */
        $startpage = null;
        /** @var string ссылка В КОНЕЦ */
        $endpage = null;
        /** @var string вторая страница слева */
        $page2left = null;
        /** @var string первая страница слева */
        $page1left = null;
        /** @var string вторая страница справа */
        $page2right = null;
        /** @var string первая страница справа */
        $page1right = null;

        if ($this->currentPage > 3) {
            $startpage = "<li><a class='nav-link' href='{$this->uri}page=1'>&laquo;</a></li>";
        }
        if ($this->currentPage > 1) {
            $back = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage - 1) . "'>&lt;</a></li>";
        }
        if ($this->currentPage > 2) {
            $page2left = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage - 2) . "'>" . ($this->currentPage - 2) . "</a></li>";
        }
        if ($this->currentPage > 1) {
            $page1left = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage - 1) . "'>" . ($this->currentPage - 1) . "</a></li>";
        }
        if ($this->currentPage < $this->countPages) {
            $page1right = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage + 1) . "'>" . ($this->currentPage + 1) . "</a></li>";
        }
        if ($this->currentPage < ($this->countPages - 1)) {
            $page2right = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage + 2) . "'>" . ($this->currentPage + 2) . "</a></li>";
        }
        if ($this->currentPage < $this->countPages) {
            $forward = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage + 1) . "'>&gt;</a></li>";
        }
        if ($this->currentPage < ($this->countPages - 2)) {
            $endpage = "<li><a class='nav-link' href='{$this->uri}page={$this->countPages}'>&raquo;</a></li>";
        }

        return '<ul class="pagination">' . $startpage . $back . $page2left . $page1left . '<li class="active"><a>' . $this->currentPage . '</a></li>' . $page1right . $page2right . $forward . $endpage . '</ul>';
    }

    public function __toString()
    {
        return $this->getHtml();
    }
}
