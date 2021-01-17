<option class="label" value="<?= $this->currency['code'] ?>"><?= $this->currency['code'] ?></option>

<?php foreach ($this->currencies as $code => $value) : ?>
    <?php if ($code !== $this->currency['code']) : ?>
        <option value="<?= $code ?>"><?= $code ?></option>
    <?php endif; ?>
<?php endforeach; ?>
