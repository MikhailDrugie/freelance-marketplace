import $ from 'jquery';

$('.account-delete').on('click', function (e) {
    e.preventDefault();
    let userId = $(this).data('user-id');
    let url = $(this).data('url');
    let urlIndex = $(this).data('url-index');
    let csrf = $(this).data('csrf');
    if (!confirm('Вы уверены, что хотите удалить аккаунт?')) return;
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: {'userId': userId, '_csrf_token': csrf},
        success: function () {
            alert('Аккаунт успешно удален');
            window.location.href = urlIndex;
        },
        error: function() {
            alert('Ошибка удаления аккаунта');
            window.reload();
        }
    });
});

$('.password-change').on('click', function (e) {
    e.preventDefault();
    window.location.href = $(this).data('url');
});