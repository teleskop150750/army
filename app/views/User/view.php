<main class="main page__main">
    <div class="container">
        <div class="row">
            <div class="col mt-5"><h1 class="mb-5">Ваш личный кабинет</h1></div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <img src="/upload/images/avatars/<?= $_SESSION['user']['img'] ?>" style="width: 100%;" class="card-img-top user__img-preview" alt="аватар" >
                <div class="card-body p-0 mt-2">
                    <button class="btn btn-primary user__img-select" type="button"
                            data-url="user/edit-avatar"
                            data-name="img"
                            data-id="<?= $_SESSION['user']['id'] ?>"
                            style="width: 100%;">
                        Изменить
                    </button>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <p class="form-label">Логин</p>
                            <p class="form-control"><?= $_SESSION['user']['login'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <p class="form-label">Email</p>
                            <p class="form-control"><?= $_SESSION['user']['email'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-primary" href="/user/logout">Выйти</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>