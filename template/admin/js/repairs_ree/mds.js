
// Отправляем форму если проходит валидацию
$('#create-data-form').submit(function(e) {
    e.preventDefault();
    if ($('#create-data-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#create-data-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 2000);
    }
});