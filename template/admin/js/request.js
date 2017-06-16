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
            var obj = JSON.parse(response);
            console.log(response);
            if(obj.result == 1){
                $("[name='part_number']").removeClass('error_part');
                $('.name-product').text(obj.mName).css('color', '#4CAF50');
                $("[name='price']").val(obj.price);
            } else {
                $('.name-product').text('not found').css('color', 'red');
                $("[name='price']").val('0');
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
    $("#order_pn").val(order_pn);
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