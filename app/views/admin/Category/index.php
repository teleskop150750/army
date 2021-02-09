<?php
/** @var array $categories */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Категории
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <a href="<?= ADMIN ?>/category/category-add" class="btn btn-primary">
                            <i class="fa fa-fw fa-plus"></i> Добавить категорию
                        </a>
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($categories as $category) : ?>
                                <tr>
                                    <td><?= $category['title'] ?></td>
                                    <td>
                                        <a href="<?= ADMIN ?>/category/category-edit?id=<?= $category['id'] ?>">
                                            <i class="fa fa-fw fa-pencil"></i>
                                        </a>
                                        <a class="delete text-danger"
                                           href="<?= ADMIN ?>/category/category-delete?id=<?= $category['id'] ?>">
                                            <i class="fa fa-fw fa-close text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->