
// Отправляем форму если проходит валидацию
$('#create-so-modal').submit(function(e) {
    e.preventDefault();
    if ($('#create-so-modal input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#create-so-modal').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 2000);
    }
});