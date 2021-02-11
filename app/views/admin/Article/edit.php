<?php
/** @var object $article */
/** @var array $categories */
/** @var array $gallery */
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактировать статью
    </h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN ?>/article/edit " method="post" data-toggle="validator" id="add">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="title">Заголовок</label>
                            <input type="text" name="title" class="form-control" id="title"
                                   placeholder="Наименование товара"
                                   value="<?= h($article->title) ?>"
                                   required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>

                        <div class="form-group">
                            <label>Родительская категория</label>
                            <?php foreach ($categories as $category) : ?>
                                <div>
                                    <label>
                                        <input type="radio" name="category_id" required
                                            <?= $article->category_id == $category['id'] ? 'checked' : null ?>
                                               value="<?= $category['id'] ?>">
                                        <?= $category['title'] ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="form-group">
                            <label for="keywords">Ключевые слова</label>
                            <input type="text" name="keywords" class="form-control" id="keywords"
                                   placeholder="Ключевые слова"
                                   value="<?= h($article->keywords) ?>">
                        </div>

                        <div class="form-group">
                            <label for="description">Описание</label>
                            <input type="text" name="description" class="form-control" id="description"
                                   placeholder="Описание"
                                   value="<?= h($article->description) ?>">
                        </div>

                        <div class="form-group has-feedback">
                            <label for="content">Контент</label>
                            <textarea name="content" id="editor1" cols="80"
                                      rows="10"><?= h($article->content) ?></textarea>
                        </div>
                        <div class="form-group">
                            <a href="/adminlte/bower_components/ckfinder/ckfinder.html?CKEditor=editor1&CKEditorFuncNum=3&langCode=ru">файлы</a>
                        </div>
                        <div class="form-group">
                            <a href="<?= ADMIN ?>/article/remove-dir" class="btn btn-success">Очистить папку</a>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="status" <?= $article->status == '1' ? 'checked' : null ?>>
                                Статус
                            </label>
                        </div>

                        <div class="form-group has-feedback">
                            <label for="img">Базовое изображение</label>
                            <input type="text" name="img" class="form-control" id="img"
                                   placeholder="img.jpg"
                                   required
                                   value="<?= $article->img ?>">
                        </div>

                        <div class="form-group has-feedback article-gallery">
                            <div><label>Галерея</label></div>
                            <div>
                                <button type="button" class="btn btn-success remove-gallery-db-all"
                                        data-id="<?= $article->id ?>">
                                    Очистить все
                                </button>
                                <button type="button" class="btn btn-success add-gallery">Добавить</button>
                            </div>
                            <?php if (!empty($gallery)) : ?>
                                <?php foreach ($gallery as $item) : ?>
                                    <div class="gallery-item" data-id="<?= $article->id ?>" data-src="<?= $item ?>">
                                        <input type="text" class="form-control" value="<?= $item ?>" required>
                                        <button type="button" class="btn btn-flat remove-gallery-db">Удалить</button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="id" value="<?= $article->id ?>">
                        <button type="submit" class="btn btn-success">Изменить</button>
                    </div>
                </form>
                <?php
                if (isset($_SESSION['form_data'])) {
                    unset($_SESSION['form_data']);
                }
                ?>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
