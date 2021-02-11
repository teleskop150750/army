<?php
/** @var array $article */
/** @var array $comments */
/** @var array $categories */
/** @var array $gallery */
?>
<div class="main__container">
    <main class="main__inner">
        <article class="article">
            <header class="article__header">
                <div class="article__img-wrapper">
                    <img class="article__img" src="/upload/images/<?= $article['img'] ?>" alt="">
                </div>
            </header>
            <div class="article__content">
                <h3 class="article__title">
                    <?= $article['title'] ?>
                </h3>
                <time datetime="<?= getArticleDateTime($article['date']) ?>" class="article__date">
                    <?= getArticleDate($article['date']) ?>
                </time>
                <div class="article__content-body">
                    <?= $article['content'] ?>
                </div>

                <?php if (!empty($gallery)) : ?>
                    <div class="article__slider">
                        <section class="article-slider slider">
                            <?php foreach ($gallery as $item) : ?>
                                <div class="slider__item">
                                    <div class="slider__item-inner">
                                        <img src="/upload/images/<?= $item['img'] ?>" alt="галерея">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    </div>
                <?php endif; ?>
                <footer class="article__footer">
                    <div class="article__info">
                        <span class="article__see">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                               class="bi bi-eye"
                               viewBox="0 0 16 16">
                            <path
                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                          </svg>
                          <?= $article['views'] ?>
                        </span>
                    </div>
                </footer>
            </div>
        </article>

        <div class="chat">
            <?php if (isset($_SESSION['user'])) : ?>
                <div class="chat-form chat__form">
                    <div class="chat-form__avatar-wrapper">
                        <img class="chat-form__avatar" src="/upload/images/avatars/<?= $_SESSION['user']['img'] ?>"
                             alt="аватарка">
                        <a href="/user/view"><h3 class="chat-form__user"><?= $_SESSION['user']['login'] ?></h3></a>
                    </div>
                    <div class="chat-form__content">
                        <form class="chat-form__inner chat-submit__form" action="/comment/add-comment" method="post">
                            <input type="hidden" name="article" value="<?= $article['id'] ?>">
                            <input type="hidden" name="user" value="<?= $_SESSION['user']['id'] ?>">
                            <textarea class="chat-form__textarea" name="comment" id="" cols="30" rows="10"></textarea>
                            <button class="chat-form__button" type="submit">Комментировать</button>
                        </form>
                    </div>
                </div>
            <?php else : ?>
                <div class="chat-login chat__form">
                    <p class="chat-login__message">
                        Чтобы оставить комментарий, необходимо <a class="chat-login__message-link" href="/user/signup">зарегистрироваться</a>
                        или <a class="chat-login__message-link" href="/user/login">войти</a>
                    </p>
                </div>
            <?php endif; ?>


            <?php foreach ($comments as $comment) : ?>
                <article class="comment" data-id="<?= $comment['id'] ?>">
                    <img class="comment__avatar" src="/upload/images/avatars/<?= $comment['img'] ?>" alt="аватарка">
                    <div class="comment__content">
                        <header class="comment__header">
                            <h3 class="comment__user">
                                <?= $comment['login'] ?>
                            </h3>
                            <time datetime="<?= getArticleDateTime($comment['date']) ?>" class="article__date">
                                <?= getArticleDate($comment['date']) ?>
                            </time>
                        </header>
                        <div class="comment__body" contenteditable="false">
                            <?= $comment['text'] ?>
                        </div>
                    </div>
                    <footer class="comment__footer">
                        <?php if (!empty($_SESSION['user'])) : ?>
                            <?php if ((int)$comment['user_id'] === (int)$_SESSION['user']['id']) : ?>
                                <button class="comment__button comment__editor">редактировать</button>
                            <?php endif; ?>
                            <?php if ((int)$comment['user_id'] === (int)$_SESSION['user']['id'] || $_SESSION['user']['role'] === 'admin') : ?>
                                <button class="comment__button comment__remove">удалить</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
    <aside class="aside main__aside">
        <section class="section-aside aside__section">
            <h2 class="section-aside__title">Категории</h2>
            <ul class="category__list">
                <?php foreach ($categories as $category) : ?>
                    <li class="category__item">
                        <a class="category__link" href="/news/<?= $category['alias'] ?>"
                           data-id="<?= $category['id'] ?>">
                            <?= $category['title'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </aside>
</div>
