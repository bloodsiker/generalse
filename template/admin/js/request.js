
$('table').tablesort();

// открыть окно добавления корзины
$('body').on('click', '#add-request-button', function() {
    $('.name-product').text('');
    $('.pn-analog').text('');
    $(".group-stocks").addClass('hide');
    $('#add-request-modal form')[0].reset();
    $('#add-request-modal').foundation('open');
});

// модальное окно (узнать цену по парт номеру)
$('body').on('click', '#price-button', function() {
    $('.name-product').text('');
    $('.pn-analog').text('');
    $(".group-stocks").addClass('hide');
    $('#price-modal form')[0].reset();
    $('#price-modal').foundation('open');
});


// Запрещаем вводить в поле все кроме цифт
function checkCurrPartNumber(d) {
    if(window.event)
    {
        if(event.keyCode == 37 || event.keyCode == 39) return;
    }
    d.value = d.value.replace(/\D/g,'');
}


//PART NUMBER SEARCH
$('[name="part_number"]').keyup(function(e) {
    //ajax e.target.value
    var part_number = e.target.value;

    $.ajax({
        url: "/adm/crm/request/price_part_ajax",
        type: "POST",
        data: {part_number : part_number},
        cache: false,
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            if(obj.result == 1){
                $("[name='part_number']").removeClass('error_part');
                $('.name-product').text(obj.mName).css('color', '#4CAF50');
                $("[name='price']").val(obj.price);
                if(obj.in_stock == 1){
                    $(".group-stocks").removeClass('hide');
                    $('.name-stock').text(obj.stock).css('color', '#4CAF50');
                    $("[name='quantity']").val(obj.quantity);
                } else {
                    $(".group-stocks").addClass('hide');
                }
                if(obj.is_analog == 1){
                    $('.pn-analog').text(obj.analog);
                }
            } else {
                $('.name-product').text('not found').css('color', 'red');
                $("[name='price']").val('0');
                $(".group-stocks").addClass('hide');
                $('.name-stock').text('');
                $("[name='quantity']").val('');
                $('.pn-analog').text('');
            }
        }
    });

    return false;
});



// Отправляем заявку на реквест
$('#add-request-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-request-form input').hasClass('is-invalid-input') || $('#add-request-form select').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#add-request-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 2000);
    }
});

//
$('#add-request-import-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-request-import-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#add-request-import-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 2000);
    }
});


// Импорт с модального окна
$('#import-edit-status-form').submit(function(e) {
    e.preventDefault();
    if ($('#import-edit-status input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#import-edit-status-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 3000);
    }
});


// Импорт парт аналогов
$('#import-analog-form').submit(function(e) {
    e.preventDefault();
    if ($('#import-analog-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#import-analog-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 3000);
    }
});


// Открываем модальное окно, и редактируем парт номер
var id_order = null;
$(document).on('click', '.edit-pn', function(e) {
    e.preventDefault();
    $('#edit-pn').foundation('open');
    var order_pn = $(this).siblings('.order_part_num').text();
    $("#order_pn").val(order_pn.trim());
    id_order = $(this).parent('td').parent('tr').data('id');
});
// Вносим изменения в модальном окне
$(document).on('click', '#send-order-pn', function(e) {
    e.preventDefault();
    var order_pn = $("#order_pn").val();
    var data = "action=edit_pn&id_order=" + id_order + "&order_pn=" + order_pn;

    $.ajax({
        url: "/adm/crm/request/request_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            if(response == 200){
                $('[data-id="' + id_order + '"]').find('.order_part_num').text(order_pn).css('color', 'green');
                $('#edit-pn form')[0].reset();
                $('#edit-pn').foundation('close');
            } else {
                alert('Ошибка! Не удалось обновить запись!');
            }
        }
    });
});


// Изменяем SO
$(document).on('click', '.edit-so', function(e) {
    e.preventDefault();
    $('#edit-so').foundation('open');
    var order_so = $(this).siblings('.order_so').text();
    $("#order_so").val(order_so.trim());
    id_order = $(this).parent('td').parent('tr').data('id');
});
$(document).on('click', '#send-order-so', function(e) {
    e.preventDefault();
    var order_so = $("#order_so").val();
    var data = "action=edit_so&id_order=" + id_order + "&order_so=" + order_so;

    $.ajax({
        url: "/adm/crm/request/request_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            if(response == 200){
                $('[data-id="' + id_order + '"]').find('.order_so').text(order_so).css('color', 'green');
                $('#edit-so form')[0].reset();
                $('#edit-so').foundation('close');
            } else {
                alert('Ошибка! Не удалось обновить запись!');
            }
        }
    });
});


// Чистим название парт номера
$(document).on('click', '.clear_goods_name', function(e) {
    e.preventDefault();
    var id_order = $(this).parent('td').parent('tr').data('id');
    var data = "action=clear_goods_name&id_order=" + id_order;

    $.ajax({
        url: "/adm/crm/request/request_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            if(response == 200){
                $('[data-id="' + id_order + '"]').find('.pn_goods_name').text('');
            } else {
                alert('Ошибка! Не удалось очистить название!');
            }
        }
    });
});


// Редиктируем статус реквеста
$(document).on('click', '.edit-status', function(e) {
    e.preventDefault();
    $('#edit-status').foundation('open');
    var order_status = $(this).siblings('.order_status').text();
    $("#order_status").val(order_status.trim());
    id_order = $(this).parent('td').parent('tr').data('id');
});
$(document).on('click', '#send-order-status', function(e) {
    e.preventDefault();
    var order_status = $("#order_status").val();
    var data = "action=edit_status&id_order=" + id_order + "&order_status=" + order_status;

    $.ajax({
        url: "/adm/crm/request/request_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            if(response == 200){
                $('[data-id="' + id_order + '"]').find('.order_status').text(order_status).css('color', 'green');
                $('#edit-status form')[0].reset();
                $('#edit-status').foundation('close');
            } else {
                alert('Ошибка! Не удалось обновить запись!');
            }
        }
    });
});



// Редактируем парт номер и аналог
var id_record = null;
$('#goods_data').on('click', '.edit-analog', function(e) {
    e.preventDefault();
    $('#edit-analog').foundation('open');
    var r_pn = $(this).parent('td').parent('tr').find('.r_part').text();
    var r_analog = $(this).parent('td').parent('tr').find('.r_analog').text();
    $("#r_pn").val(r_pn.trim());
    $("#r_analog").val(r_analog.trim());
    id_record = $(this).parent('td').parent('tr').data('id');
    console.log(r_pn);
});
//Вносим изменения в модальном окне
$(document).on('click', '#send-pn-analog', function(e) {
    e.preventDefault();
    var r_pn = $("#r_pn").val();
    var r_analog = $("#r_analog").val();
    var data = "action=edit_pn_analog&id_record=" + id_record + "&part_number=" + r_pn + "&part_analog=" + r_analog;

    $.ajax({
        url: "/adm/crm/request/request_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            if(response == 200){
                $('[data-id="' + id_record + '"]').find('.r_part').text(r_pn).css('color', 'green');
                $('[data-id="' + id_record + '"]').find('.r_analog').text(r_analog).css('color', 'green');
                $('#edit-analog form')[0].reset();
                $('#edit-analog').foundation('close');
            } else {
                alert('Ошибка! Не удалось обновить запись!');
            }
        }
    });
});



// Upload ajax file progress bar
(function() {

    var bar = $('.upload-bar');
    var percent = $('.upload-percent');
    var status = $('#status');

    $('#price-upload').ajaxForm({
        beforeSend: function() {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
            //console.log(percentVal, position, total);
        },
        success: function() {
            var percentVal = '100%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        complete: function(xhr) {
            //status.html(xhr.responseText);
            status.html('Файл успешно загружен!');
        }
    });

})();