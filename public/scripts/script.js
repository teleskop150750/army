const navLinks = document.querySelectorAll('.nav__link');
if (navLinks.length !== 0) {
    navLinks.forEach((link) => {
        if (link.href === location.href) {
            link.classList.add('nav__link--active');
        }
    });
}


const categories = document.querySelectorAll('.category__link');
if (categories.length !== 0) {
    const catTitle = document.querySelector('.section-aside__title').dataset.id;

    categories.forEach((category) => {
        const categoryId = category.dataset.id;
        if (categoryId === catTitle) {
            category.classList.add('category__link--active');
        }

        category.addEventListener('click', async function (e) {
            e.preventDefault();

            let url = location.origin + '/news';
            let formData = new FormData();

            if (!category.classList.contains('category__link--active')) {
                categories.forEach((item) => {
                    item.classList.remove('category__link--active');
                });
                const categoryId = this.dataset.id;

                this.classList.add('category__link--active');

                formData.append('category', categoryId);

                let newURL = url + "?category=" + categoryId;
                newURL = newURL.replace('&&', '&');
                newURL = newURL.replace('?&', '?');
                history.replaceState({}, '', newURL);
            } else {
                this.classList.remove('category__link--active');
                history.replaceState({}, '', url);
            }

            url = location.origin + '/news/page';
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
            });

            const result = await response.text();
            const mainInner = pageBody.querySelector('.main__inner');
            mainInner.innerHTML = result;
        });
    })
}

const pageBody = document.querySelector('.main__container');
if (pageBody) {
    pageBody.addEventListener('click', async function (e) {
        if (e.target.classList.contains('pagination__button')
            && !e.target.classList.contains('pagination__button--active')
        ) {
            const button = e.target;
            const page = button.dataset.page;

            let formData = new FormData();
            formData.append('page', page);
            const category = pageBody.querySelector('.category__link--active');
            if (category) {
                const categoryId = category.dataset.id;
                formData.append('category', categoryId);
            }

            let url = location.origin + '/news/page';

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
            });
            const result = await response.text();
            const mainInner = pageBody.querySelector('.main__inner');
            mainInner.innerHTML = result;
            url = location.href.replace(/page(.+?)(&|$)/g, '');
            let newURL = url + (location.search ? "&" : "?") + "page=" + page;
            newURL = newURL.replace('&&', '&');
            newURL = newURL.replace('?&', '?');
            history.replaceState({}, '', newURL);
        }
    });
}

const imgSelectButton = document.querySelector('.form__img-select');
if (imgSelectButton) {
    new AjaxUpload(imgSelectButton, {
        action: location.origin + '/' + imgSelectButton.dataset.url + "?upload=1",
        data: {name: imgSelectButton.dataset.name},
        name: imgSelectButton.dataset.name,
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|jpeg|gif)$/i.test(ext))) {
                alert('Ошибка! Разрешены только картинки');
                return false;
            }
            const img = document.querySelector('.form__img-preview');
            img.src = '/upload/images/avatars/load-avatar.jpeg';
        },
        onComplete: function (file, response) {
            setTimeout(function () {
                response = JSON.parse(response);
                const img = document.querySelector('.form__img-preview');
                img.src = '/upload/images/avatars/' + response.file;
                const input = document.querySelector('.form__file-img');
                input.value = response.file;
            }, 0);
        }
    });
}

const userImgSelectButton = document.querySelector('.user__img-select');
if (userImgSelectButton) {
    new AjaxUpload(userImgSelectButton, {
        action: location.origin + '/' + userImgSelectButton.dataset.url + "?upload=1",
        data: {
            id: userImgSelectButton.dataset.id,
            name: userImgSelectButton.dataset.name,
        },
        name: userImgSelectButton.dataset.name,
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|jpeg|gif)$/i.test(ext))) {
                alert('Ошибка! Разрешены только картинки');
                return false;
            }
            const img = document.querySelector('.user__img-preview');
            img.src = '/upload/images/avatars/load-avatar.jpeg';
        },
        onComplete: function (file, response) {
            setTimeout(function () {
                response = JSON.parse(response);
                const img = document.querySelector('.user__img-preview');
                img.src = '/upload/images/avatars/' + response.file;
            }, 0);
        }
    });
}

const commentForm = document.querySelector('.chat-form__inner');
if (commentForm) {
    commentForm.addEventListener('submit', function (e) {
        const mess = this.querySelector('.chat-form__textarea').value.trim();
        if (mess === '') {
            e.preventDefault();
            return false;
        }
    });
}

(() => {
    let contentDefault;
    const commentsRemove = document.querySelectorAll('.comment__remove');
    if (commentsRemove) {
        commentsRemove.forEach((commentRemove) => {
            commentRemove.addEventListener('click', async function () {
                const comment = this.closest('.comment');
                const commentId = comment.dataset.id;
                const contentDiv = comment.querySelector('.comment__body');
                const content = contentDiv.textContent.trim();
                const btn1 = comment.querySelector('.comment__editor');
                const btn2 = comment.querySelector('.comment__remove');

                if (!this.classList.contains('comment__save')) {
                    let formData = new FormData();
                    formData.append('id', commentId);

                    const url = location.origin + '/comment/delete-comment';
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData,
                    });
                    const result = await response.text();
                    console.log(result);
                    if (result === '1') {
                        comment.remove();
                    }
                } else {
                    if (content === '') {
                        contentDiv.textContent = contentDefault;
                    } else {
                        let formData = new FormData();
                        formData.append('data', content);
                        formData.append('id', commentId);
                        const url = location.origin + '/comment/update-comment';
                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                        });
                    }
                    btn1.classList.remove('comment__cancel')
                    btn2.classList.remove('comment__save')
                    btn1.textContent = 'редактировать';
                    btn2.textContent = 'удалить';
                    contentDiv.setAttribute("contenteditable", false);
                }
            });
        });
    }

    const commentsEditor = document.querySelectorAll('.comment__editor');
    if (commentsEditor) {
        commentsEditor.forEach((commentEditor) => {
            commentEditor.addEventListener('click', function () {
                const comment = this.closest('.comment');
                const contentDiv = comment.querySelector('.comment__body');
                const content = contentDiv.textContent.trim();
                const btn1 = comment.querySelector('.comment__editor');
                const btn2 = comment.querySelector('.comment__remove');

                if (!this.classList.contains('comment__cancel')) {
                    contentDefault = content;
                    btn1.classList.add('comment__cancel');
                    btn2.classList.add('comment__save');
                    btn1.textContent = 'отмена';
                    btn2.textContent = 'сохранить';

                    contentDiv.setAttribute("contenteditable", true);
                    contentDiv.focus();
                    console.log(contentDiv);
                } else {
                    btn1.classList.remove('comment__cancel')
                    btn2.classList.remove('comment__save')
                    btn1.textContent = 'редактировать';
                    btn2.textContent = 'удалить';

                    contentDiv.textContent = contentDefault;
                    contentDiv.setAttribute("contenteditable", false);
                }
            });
        });
    }
})();

$('.article-slider').slick({
    infinite: true,
    arrows: true,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            }
        },
    ]
});


var player = new Playerjs({replace: "video"});
