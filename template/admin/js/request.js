// открыть окно добавления корзины
$('body').on('click', '#add-request-button', function() {
    $('.name-product').text('');
    $('#add-request-modal form')[0].reset();
    $('#add-request-modal').foundation('open');
});

// модальное окно (узнать цену по парт номеру)
$('body').on('click', '#price-button', function() {
    $('.name-product').text('');
    $('#price-modal form')[0].reset();
    $('#price-modal').foundation('open');
});


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
                $(".group-stocks").removeClass('hide');
                $('.name-stock').text(obj.stock).css('color', '#4CAF50');
                $("[name='quantity']").val(obj.quantity);
            } else {
                $('.name-product').text('not found').css('color', 'red');
                $("[name='price']").val('0');
                $(".group-stocks").addClass('hide');
                $('.name-stock').text('');
                $("[name='quantity']").val('');
            }
        }
    });

    return false;
});


$('#add-request-import-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-request-import-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#add-request-import-form').find('button').prop('disabled', true);
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