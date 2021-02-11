$('.delete').click(function () {
    var res = confirm('Подтвердите действие');
    if (!res) {
        return false;
    }
});

$('.sidebar-menu a').each(function () {
    var location = window.location.protocol + '//' + window.location.host + window.location.pathname;
    var link = this.href;
    if (link == location) {
        $(this).parent().addClass('active');
        $(this).closest('.treeview').addClass('active');
    }
});

// CKEDITOR.replace('editor1');
const ckeditor = document.querySelector('#editor1');
if (ckeditor) {
    var editor = CKEDITOR.replace('editor1');
    CKFinder.setupCKEditor(editor);
}

$('#reset-filter').click(function () {
    $('#filter input[type=radio]').prop('checked', false);
    return false;
});

const buttonAddGallery = document.querySelector('.add-gallery');
if (buttonAddGallery) {
    buttonAddGallery.addEventListener('click', function () {
        const wrapper = document.querySelector('.article-gallery');
        wrapper.insertAdjacentHTML('beforeend', ' <div class="gallery-item"><input type="text" name="gallery[]" placeholder="img.jpg" class="form-control" required><button type="button" class="btn btn-flat remove-gallery">Удалить</button></div>');
    });
}

const wrapperGallery = document.querySelector('.article-gallery');

if (wrapperGallery) {
    wrapperGallery.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-gallery')) {
            const galleryItem = e.target.closest('.gallery-item');
            galleryItem.remove();
        }

        if (e.target.classList.contains('remove-gallery-db')) {
            var res = confirm('Подтвердите действие');
            if (!res) {
                return false;
            }
            const galleryItem = e.target.closest('.gallery-item');
            const id = galleryItem.dataset.id;
            const src = galleryItem.dataset.src;
            const $this = galleryItem;
            console.log(id);
            console.log(src);
            console.log($this);
            $.ajax({
                url: adminpath + '/article/delete-gallery',
                data: {id: id, src: src},
                type: 'POST',
                success: function (res) {
                    setTimeout(function () {
                        if (res == 1) {
                            $this.remove();
                        }
                    }, 1000);
                },
                error: function () {
                    setTimeout(function () {
                        alert('Ошибка');
                    }, 1000);
                }
            });
        }
    })
}

const avatarSelectButton = document.querySelector('.admin__avatar-select');
if (avatarSelectButton) {
    new AjaxUpload(avatarSelectButton, {
        action: location.origin + '/' + avatarSelectButton.dataset.url + "?upload=1",
        data: {
            name: avatarSelectButton.dataset.name,
        },
        name: avatarSelectButton.dataset.name,
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|jpeg|gif)$/i.test(ext))) {
                alert('Ошибка! Разрешены только картинки');
                return false;
            }
            const img = document.querySelector('.admin__avatar-preview');
            img.src = location.origin + '/upload/images/avatars/load-avatar.jpeg';
        },
        onComplete: function (file, response) {
            setTimeout(function () {
                response = JSON.parse(response);
                const img = document.querySelector('.admin__avatar-preview');
                img.src = location.origin + '/upload/images/avatars/' + response.file;
                const input = document.querySelector('.admin__file-avatar');
                input.value = response.file;
            }, 0);
        }
    });
}