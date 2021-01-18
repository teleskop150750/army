<?php

/** @var array $category */
$parent = isset($category['children']);

// родитель?
if ($parent) {
    $delete = '<i class="fa fa-fw fa-close"></i>';
} else {
    $delete = '<a href="' . ADMIN . '/category/delete?id=' . $id . '" class="delete"><i class="fa fa-fw fa-close text-danger"></i></a>';
}
?>

<p class="item-p">
    <a class="list-group-item" href="<?= ADMIN ?>/category/edit?id=<?= $id ?>"><?= $category['title'] ?></a>
    <span><?= $delete; ?></span>
</p>
<?php if ($parent): ?>
    <div class="list-group">
        <?= $this->getMenuHtml($category['children']) ?>
    </div>
<?php endif; ?>
