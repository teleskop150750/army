<?php
/** @var object $category */
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование фильтра <?= h($category->title); ?>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN ?>/category/category-edit" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="value">Наименование</label>
                            <input type="text" name="title" class="form-control" id="value" placeholder="Наименование"
                                   required value="<?= h($category->title); ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="id" value="<?= $category->id ?>">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->