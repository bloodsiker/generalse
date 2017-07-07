
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


// Отмечаем зеленным цветом выбраный чекбокс
var checkColor = function (event) {

    var label = $(event.target).siblings('[for="'+event.target.id+'"]');
    if (event.target.checked) {
        label.css('color', 'green');
    } else {
        label.css('color', '#fff');

    }

};