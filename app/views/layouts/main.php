<!DOCTYPE html>
<html class="page" lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->getMeta() ?>
    <link rel="shortcut icon" href="/favicon.png" type="image/png">
    <link rel="stylesheet" href="/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/style.css">
</head>

<body class="page__body">
<header class="header page__header">
    <div class="header__container">
        <div class="header-logo header__logo">
            <a href="/" class="header-logo__link">
                <img class="header-logo__img" src="/img/logo.png" srcset="/img/logo-2x.png 2x" alt="логотип">
                <p class="header-logo__text">
                    Министерство обороны<br>
                    Российской Федерации<br>
                    (Минообороны России)
                </p>
            </a>
        </div>

        <nav class="nav header__nav">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="/" class="nav__link">Главная</a>
                </li>
                <li class="nav__item">
                    <a href="/news" class="nav__link">Новости</a>
                </li>
                <li class="nav__item">
                    <a href="/about" class="nav__link">О нас</a>
                </li>
                <li class="nav__item">
                    <a href="/documents" class="nav__link">Документы</a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<div class="content">
    <?= $content ?>
</div>

<footer class="footer page__footer">
    <div class="footer__container">
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Rerum, est?</p>
    </div>
</footer>
<script src="/scripts/jquery-3.5.1.min.js"></script>
<script src="/scripts/validator.min.js"></script>
<script src="/scripts/ajaxupload.js"></script>
<script src="/scripts/script.js"></script>
</body>

</html>