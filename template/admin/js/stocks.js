
//$('table').tablesort();

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


$(document).ready(function () {
    let subtype_td = $('.subtype_td');
    let subtypes = [];
    for (let i = 0; i < subtype_td.length; i++) {
        subtypes.push($(subtype_td[i]).html())
    }
    subtypes = _.uniq(subtypes);
    subtypes.forEach(value => {
        $('#filterSuptype').append(`<option value="${value}">${value}</option>`)
    });

    console.log(subtypes);
});
let filterSubtype = function (e) {
    $('#wait').removeClass('hide');

    setTimeout(() => {
        if (e.target.value) {
            let subtype_td = $('.subtype_td');
            for (let i = 0; i < subtype_td.length; i++) {
                if (subtype_td[i].innerHTML !== e.target.value) {
                    $(subtype_td[i]).parent('.goods').hide();
                } else {
                    $(subtype_td[i]).parent('.goods').show();
                }
            }
        } else {
            $('.goods').show();
        }
        $('#wait').addClass('hide');

    }, 10);



};