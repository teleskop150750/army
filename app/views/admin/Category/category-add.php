<?php
/** @var array $group */
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новый фильтр
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN ?>/category/category-add" method="post" data-toggle="validator" id="add">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="value">Наименование</label>
                            <input type="text" name="title" class="form-control" id="value" placeholder="Наименование"
                                   required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
