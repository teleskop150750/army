<?php
/** @var array $categories */
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
                <form action="<?= ADMIN ?>/article/add" method="post" data-toggle="validator" id="add">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="title">Заголовок</label>
                            <input type="text" name="title" class="form-control" id="title"
                                   placeholder="Название статьи"
                                   value="<?php isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>"
                                   required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>

                        <div class="form-group">
                            <label>Родительская категория</label>
                            <?php foreach ($categories as $category) : ?>
                                <div>
                                    <label>
                                        <input type="radio" name="category_id" required
                                               value="<?= $category['id'] ?>">
                                        <?= $category['title'] ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="form-group">
                            <label for="keywords">Ключевые слова</label>
                            <input type="text" name="keywords" class="form-control" id="keywords"
                                   placeholder="Ключевые слова"
                                   value="<?php isset($_SESSION['form_data']['keywords']) ? h($_SESSION['form_data']['keywords']) : null; ?>">
                        </div>

                        <div class="form-group">
                            <label for="description">Описание</label>
                            <input type="text" name="description" class="form-control" id="description"
                                   placeholder="Описание"
                                   value="<?php isset($_SESSION['form_data']['description']) ? h($_SESSION['form_data']['description']) : null; ?>">
                        </div>

                        <div class="form-group has-feedback">
                            <label for="editor1">Контент</label>
                            <textarea name="content" id="editor1" cols="80"
                                      rows="10"><?php $_SESSION['form_data']['content'] ?? null; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="status" checked> Статус
                            </label>
                        </div>

                        <div class="form-group has-feedback">
                            <label for="img">Базовое изображение</label>
                            <input type="text" name="img" class="form-control" id="img"
                                   placeholder="img.jpg"
                                   required
                                   value="article-default.jpeg">
                        </div>

                        <div class="form-group has-feedback article-gallery">
                            <div><label>Галерея</label></div>
                            <div>
                                <button type="button" class="btn btn-success add-gallery">Добавить</button>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
                <?php if (isset($_SESSION['form_data'])) {
                    unset($_SESSION['form_data']);
                } ?>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
