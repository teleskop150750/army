<?php
/** @var object $user */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование пользователя
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN ?>/user/edit" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="login">Логин</label>
                            <input type="text" class="form-control" name="login" id="login"
                                   value="<?= h($user->login) ?>" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" name="password" id="password"
                                   placeholder="Введите пароль, если хотите его изменить">
                        </div>
                        <div class="form-group has-feedback">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                   value="<?= h($user->email) ?>" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label>Роль</label>
                            <select name="role" id="role" class="form-control">
                                <option value="user"
                                    <?php
                                    if ($user->role === 'user') {
                                        echo ' selected';
                                    }
                                    ?>>
                                    Пользователь
                                </option>
                                <option value="admin"
                                    <?php
                                    if ($user->role === 'admin') {
                                        echo ' selected';
                                    }
                                    ?>>
                                    Администратор
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card" style="width: 300px;">
                            <img src="<?= PATH ?>/upload/images/avatars/<?= $user->img ?>" width="300" class="card-img-top admin__avatar-preview" alt="аватар" >
                            <div class="card-body">
                                <button class="btn btn-primary admin__avatar-select" type="button"
                                        data-url="user/add-avatar"
                                        data-name="img"
                                        style="width: 300px;">
                                    изменить
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <input class="admin__file-avatar" name="img" type="hidden" value="<?= $user->img ?>">
                        <input type="hidden" name="id" value="<?= $user->id ?>">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
