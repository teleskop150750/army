<main class="main page__main">
    <div class="container-lg">
        <div class="col">
            <div class="row"><h1 class="mb-5">Ваш личный кабинет</h1></div>
        </div>
        <div class="row">
            <div class="col-md-4 ">
                <img src="/avatars/<?= $_SESSION['user']['img'] ?>" class="img-thumbnail" alt="аватар">
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