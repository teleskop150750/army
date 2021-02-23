<?php

/** @var array $users */
/** @var Pagination $pagination */
/** @var int $total */

use core\libs\Pagination;

?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список пользователей
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
                                <th>Логин</th>
                                <th>Email</th>
                                <th>Роль</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $user) : ?>
                                <td><?= $user->id ?></td>
                                <td><?= h($user->login) ?></td>
                                <td><?= ($user->email) ?></td>
                                <td><?= $user->role ?></td>
                                <td>
                                    <a href="<?= ADMIN ?>/user/edit?id=<?= $user->id ?>">
                                        <i class="fa fa-fw fa-eye"></i>
                                    </a>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?=count($users) ?> пользователей из <?= $total ?>)</p>
                        <?php if ($pagination->countPages > 1) : ?>
                            <?=$pagination;?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->