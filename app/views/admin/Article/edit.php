<?php
/** @var object $article */
/** @var array $categories */
/** @var array $gallery */
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новая статья
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
                            <label>
                                <input type="checkbox" name="status" <?= $article->status == '1' ? 'checked' : null ?>> Статус
                            </label>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4">
                                <div class="box box-danger box-solid file-upload">
                                    <div class="box-header">
                                        <h3 class="box-title">Базовое изображение</h3>
                                    </div>
                                    <div class="box-body">
                                        <div id="single" class="btn btn-success" data-url="article/add-image"
                                             data-name="single">Выбрать файл
                                        </div>
                                        <div class="single">
                                            <img src="/upload/images/<?= $article->img ?>" alt="" style="width: 100px;">
                                        </div>
                                    </div>
                                    <div class="overlay">
                                        <i class="fa fa-refresh fa-spin"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="box box-primary box-solid file-upload">
                                    <div class="box-header">
                                        <h3 class="box-title">Картинки галереи</h3>
                                    </div>
                                    <div class="box-body">
                                        <div id="multi" class="btn btn-success" data-url="article/add-image"
                                             data-name="multi">Выбрать файл
                                        </div>
                                        <div class="multi">
                                            <?php if (!empty($gallery)) : ?>
                                                <?php foreach ($gallery as $item) : ?>
                                                    <img src="/upload/images/<?= $item ?>" alt=""
                                                         style="max-height: 80px; cursor: pointer;"
                                                         data-id="<?= $article->id ?>" data-src="<?= $item ?>"
                                                         class="del-item">
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="overlay">
                                        <i class="fa fa-refresh fa-spin"></i>
                                    </div>
                                </div>
                            </div>
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
