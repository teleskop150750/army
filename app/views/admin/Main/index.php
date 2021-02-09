<?php
/** @var int $countArticles */
/** @var int $countUsers */
/** @var int $countCategories */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Панель управления</h1>
    <ol class="breadcrumb">
        <li>
            <a href="<?= ADMIN ?>"><i class="fa fa-dashboard"></i> Главная</a>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= $countArticles ?></h3>
                    <p>Статьи</p>
                </div>
                <a href="<?= ADMIN ?>/article" class="small-box-footer">
                    Больше информации
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?= $countUsers ?></h3>
                    <p>Пользователи</p>
                </div>
                <a href="<?= ADMIN ?>/category" class="small-box-footer">
                    Больше информации
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= $countCategories ?></h3>
                    <p>Категории</p>
                </div>
                <a href="<?= ADMIN ?>/category" class="small-box-footer">
                    Больше информации
                </a>
            </div>
        </div>
        <!-- ./col -->
    </div>

</section>
<!-- /.content -->
