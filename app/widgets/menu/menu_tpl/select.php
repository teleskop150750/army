<?php
/** @var int $id */
/** @var string $tab */
/** @var array $category */
?>

<?php $parent_id = core\App::$app->getProperty('parent_id'); ?>
    <option value="<?= $id ?>"
        <?php if ($id === (int)$parent_id) {
            echo 'selected';
        } ?>
        <?php if (isset($_GET['id']) && $id === (int)$_GET['id']) {
            echo 'disabled';
        } ?>>
        <?= $tab . $category['title'] ?>
    </option>

<?php if (isset($category['children'])) : ?>
    <?= $this->getMenuHtml($category['children'], '&nbsp;' . $tab . '-') ?>
<?php endif; ?>