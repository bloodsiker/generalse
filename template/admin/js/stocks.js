
$('table').tablesort();

$(document).on('change', '#stock', function (e) { // склады
    console.log(e.target.value);
});

$('#stock_filter').submit(function(e) {
    e.preventDefault();

    $('#stock_filter').find('button').prop('disabled', true);
    $('#wait').removeClass('hide');
    setTimeout(function () {
        e.target.submit()
    }, 2000);
});


let checkAllCheckbox = function (event, group) {

    let label = $('.check');
    if (event.target.checked) {
        $(group || document).find("input[type=checkbox]").prop('checked', true);
        $(group || document).find(label).css('color', 'green');
    } else {
        $(group || document).find("input[type=checkbox]").prop('checked', false);
        $(group || document).find(label).css('color', '#fff');
    }

};

// Отмечаем зеленным цветом выбраный чекбокс
let checkColor = function (event) {

    let label = $(event.target).siblings('[for="'+event.target.id+'"]');
    if (event.target.checked) {
        label.css('color', 'green');
    } else {
        label.css('color', '#fff');
    }
};