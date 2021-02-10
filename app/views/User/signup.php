<main class="main page__main">
    <div class="container-lg">
        <div class="col-md-6">
            <div class="row mt-5">
                <div class="col"><h1 class="mb-5">Регистрация</h1></div>
            </div>
            <div class="row">
                <div class="col">
                    <form method="post" action="/user/signup" id="signup" role="form" data-toggle="validator">
                        <div class="form-group has-feedback">
                            <label for="login">Логин</label>
                            <input type="text" name="login" class="form-control" id="login" placeholder="Логин"
                                   maxlength="20"
                                   value="<?= isset($_SESSION['form_data']['login']) ? h($_SESSION['form_data']['login']) : ''; ?>"
                                   required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="password">Пароль</label>
                            <input type="password" name="password" class="form-control" id="password"
                                   placeholder="Пароль"
                                   data-error="Пароль должен включать не менее 6 символов" data-minlength="6"
                                   value="<?= isset($_SESSION['form_data']['password']) ? h($_SESSION['form_data']['password']) : ''; ?>"
                                   required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Email"
                                   value="<?= isset($_SESSION['form_data']['email']) ? h($_SESSION['form_data']['email']) : ''; ?>"
                                   required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>

                        <div class="card" style="width: 18rem;">
                            <img src="/upload/images/avatars/avatar-default.jpg" class="card-img-top form__img-preview" alt="аватар" >
                            <div class="card-body">
                                <h5 class="card-title">Аватар</h5>
                                <button class="btn btn-primary form__img-select" type="button"
                                        data-url="user/add-avatar"
                                        data-name="img">
                                    Выбрать
                                </button>
                            </div>
                        </div>
                        <input class="form__file-img" name="img" type="hidden" value="avatar-default.jpg">
                        <button type="submit" class="btn btn-primary my-5">Зарегистрироваться</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <?php if (isset($_SESSION['signup-error'])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['signup-error'];
                            unset($_SESSION['signup-error']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['signup'])) : ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['signup'];
                            unset($_SESSION['signup']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>


            <?php
            unset($_SESSION['avatar']);
            if (isset($_SESSION['form_data'])) {
                unset($_SESSION['form_data']);
            }
            ?>
        </div>
    </div>
</main>