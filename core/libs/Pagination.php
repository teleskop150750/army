<?php

namespace core\libs;

class Pagination
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

    public function getHtml(): string
    {
        /** @var string ссылка НАЗАД */
        $back = null;
        /** @var string ссылка ВПЕРЕД */
        $forward = null;
        /** @var string ссылка В НАЧАЛО */
        $startPage = null;
        /** @var string ссылка В КОНЕЦ */
        $endPage = null;
        /** @var string вторая страница слева */
        $page2left = null;
        /** @var string первая страница слева */
        $page1left = null;
        /** @var string вторая страница справа */
        $page2right = null;
        /** @var string первая страница справа */
        $page1right = null;

        if ($this->currentPage > 3) {
            $startPage = "<li class='pagination__item'>
                <button class='pagination__button' data-page='1'>
                   <svg class='pagination__button-arrow' focusable='false' viewBox='0 0 24 24' aria-hidden='true'>
                            <path d='M18.41 16.59L13.82 12l4.59-4.59L17 6l-6 6 6 6zM6 6h2v12H6z'></path>
                        </svg>
                </button>
            </li>";
        }
        if ($this->currentPage > 1) {
            $back = "<li class='pagination__item'>
                <button class='pagination__button' data-page=" . ($this->currentPage - 2) . ">
                    <svg class='pagination__button-arrow' focusable='false' viewBox='0 0 24 24' aria-hidden='true'>
                        <path d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'></path>
                    </svg>
                </button>
            </li>";
        }
        if ($this->currentPage > 2) {
            $page2left = "<li class='pagination__item'>
                <button class='pagination__button' data-page=" . ($this->currentPage - 1) . ">"
                . ($this->currentPage - 2)
                . "</button>
            </li>";
        }
        if ($this->currentPage > 1) {
            $page1left = "<li class='pagination__item'>
                <button class='pagination__button' data-page=" . ($this->currentPage - 1) . ">"
                . ($this->currentPage - 1)
                . "</button>
            </li>";
        }
        if ($this->currentPage < $this->countPages) {
            $page1right = "<li class='pagination__item'>
                <button class='pagination__button' data-page=" . ($this->currentPage + 1) . ">"
                . ($this->currentPage + 1)
                . "</button>
            </li>";
        }
        if ($this->currentPage < ($this->countPages - 1)) {
            $page2right = "<li class='pagination__item'>
                <button class='pagination__button' data-page=" . ($this->currentPage + 2) . ">"
                . ($this->currentPage + 2)
                . "</button>
            </li>";
        }
        if ($this->currentPage < $this->countPages) {
            $forward = "
            <li class='pagination__item'>
                <button class='pagination__button' data-page=" . ($this->currentPage + 1) . ">
                    <svg class='pagination__button-arrow' focusable='false' viewBox='0 0 24 24' aria-hidden='true'>
                         <path d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'></path>
                    </svg>
                </button>
            </li>";
        }
        if ($this->currentPage < ($this->countPages - 2)) {
            $endPage = "
            <li class='pagination__item'>
                <button class='pagination__button' data-page='{$this->countPages}'>
                    <svg class='pagination__button-arrow' focusable='false' viewBox='0 0 24 24' aria-hidden='true'>
                        <path d='M5.59 7.41L10.18 12l-4.59 4.59L7 18l6-6-6-6zM16 6h2v12h-2z'></path>
                    </svg>
                </button>
            </li>";
        }

        return '<nav class="pagination" aria-label="pagination navigation">
                    <ul class="pagination__list">'
            . $startPage
            . $back
            . $page2left
            . $page1left
            . '<li class="pagination__item">
                    <button class="pagination__button pagination__button--active">'
            . $this->currentPage
            . '</button>
            </li>'
            . $page1right
            . $page2right
            . $forward
            . $endPage
            . '</ul>
        </nav>';
    }

    public function __toString()
    {
        return $this->getHtml();
    }
}
