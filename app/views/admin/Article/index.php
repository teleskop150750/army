<?php
/** @var inr $total */
/** @var array $articles */
/** @var core\libs\PaginationAdmin $pagination */
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список статей
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Категория</th>
                                <th>Заголовок</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($articles as $article) : ?>
                                <tr>
                                    <td><?= $article['id'] ?></td>
                                    <td><?= $article['cat'] ?></td>
                                    <td><?= $article['title'] ?></td>
                                    <td><?= $article['status'] ? 'On' : 'Off' ?></td>
                                    <td>
                                        <a href="<?= ADMIN ?>/article/edit?id=<?= $article['id'] ?>">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                        <a class="delete" href="<?= ADMIN ?>/article/delete?id=<?= $article['id'] ?>">
                                            <i class="fa fa-fw fa-close text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?= count($articles); ?> / <?= $total ?>)</p>
                        <?php if ($pagination->countPages > 1) : ?>
                            <?= $pagination; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
