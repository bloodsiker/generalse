

$('#add-batch-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-batch-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#add-batch-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 3000);
    }
});


// Поиск по чекбоксам
var checkedArr = [];
$('#search').focus(function (event) {

    var checkedLength = $('label.check:not(.all)').length;
    console.log();
    for(var i = 0; i < checkedLength; i++) {
        checkedArr.push({id: i, text:$('label.check:not(.all)')[i].innerText})
    }
});

$('#search').keyup(function (event) {

    var value = $('#search').val();

    if(value.length > 0){
        $('label.check.all').parent().css('display', 'none');
    } else {
        $('label.check.all').parent().css('display', '');
    }

    // Нет совпадений
    var arr_not_search = checkedArr.filter(function (key) {
        return key.text.toLowerCase().indexOf(event.target.value.toLowerCase()) === -1;
    });

    $('label.check:not(.all)').parent().removeAttr('style');

    arr_not_search.forEach(function (key) {
        var label_not = $('label.check:not(.all)');
        var label = $('label.check');
        label_not[key.id].parentNode.style.display = 'none';
        label.siblings("input[type=checkbox]").prop('checked', false);
        label.css('color', '#fff');
    });
});


//Клик по кнопке Генерация excel
$('#form-generate-excel').submit(function(e) {
    e.preventDefault();

    $('#form-generate-excel').find('button').prop('disabled', true);
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

// открыть окно добавления корзины
$('body').on('click', '#add-checkout-button', function() {
    $('#add-checkout-modal').foundation('open');
    hideAllInput();
});

//PART NUMBER SEARCH
//$('[name="part_number"]').keyup(function(e) {
//    //test search
//    $('.name-product').text(function() {
//        if (e.target.value == '111222') {
//            return 'Lenovo a 1000'
//        } else $('.name-product').text('');
//    })
//});


// Сравниваем кол-во товаров на складе, с введенным пользователем,
// если позьлователь ввел больше максимального значения, подставляем максимальное значение
function checkQuantity() {
    $(document).on('keyup', '[type="number"]', function (e) {
        var max = $("[name='quantity']").attr('max');
        if (e.target.value > parseInt(max)) {
            e.target.value = max
        }
        if (e.target.value < 1) {
            e.target.value = 1
        }
    });
}


//PART NUMBER SEARCH
$('[name="part_number"]').keyup(function(e) {
    //ajax e.target.value
	var stock = $('#stock').val();
	var id_partner = $('#id_partner').val();
    var part_number = e.target.value;
    $.ajax({
        url: "/adm/crm/orders_part_num_ajax",
        type: "POST",
        data: {part_number : part_number, stock : stock, id_partner : id_partner},
        cache: false,
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(obj);
            console.log(response);
            if(response == 0){
                $("[name='part_number']").addClass('error_part');
                $('.name-product').text('not found').css('color', 'red');
                $('.quantity-product').text('');
            } else {
                //console.log(obj[0].mName);
                $('.name-product').text(obj.goods_name).css('color', '#4CAF50');
                $("[name='part_number']").removeClass('error_part');
                if(obj.quantity > 0){
                    $('.quantity-product').text(obj.quantity).css('color', '#4CAF50');
                    $("[name='quantity']").attr("max", obj.quantity);
                    checkQuantity();
                } else {
                    $('.quantity-product').text(obj.quantity).css('color', 'red');
                }
            }
        }
    });

    return false;
});

function hideAllInput() {
    $('[name="service_order"], [name="part_number"], [name="quantity"], [name="note"]')
        .removeAttr('required')
        .parent().hide();
    $('.name-product').text('');
	$('.quantity-product').text('');
	$('[name="part_number"]').val('');
	$('[name="note"]').val('');
}

$('#id_partner').change(function(e){
    hideAllInput();
    $('#stock').val(0).change();
});

$('#stock').change(function(e) {
    switch (e.target.value) {
        case 'OK (Выборгская, 104)':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            $('[name="note"]').parent().show();
            break;
        case 'OK':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            $('[name="note"]').parent().show();
            break;
        case 'BAD':
            hideAllInput();
            $('[name="part_number"], [name="quantity"]')
                .attr('required', '')
                .parent().show();
            $('[name="note"]').parent().show();
            break;
        case 'Not Used':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            $('[name="note"]').parent().show();
            break;
        case 'Restored':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            $('[name="note"]').parent().show();
            break;
        case 'Dismantling':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            $('[name="note"]').parent().show();
            break;
        default:

    }
});


// добавить корзину
$('#add-checkout-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-checkout-form input').hasClass('is-invalid-input') || $('#add-checkout-form select').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } if($('.name-product').text() == 'not found') {
        return false;
    } else {
        var newCheckout = $(this).serializeObject(); // получение данных в объекте
        //newCheckout.date = new Date().getDate().toString() + '-' + (new Date().getMonth() + 1).toString() + '-' + new Date().getFullYear().toString();
        newCheckout.goods_name = $('.name-product').text();
        var json = JSON.stringify(newCheckout); // json
        console.log(json); // send to server json AJAX
        $.ajax({
            url: "/adm/crm/orders_ajax",
            type: "POST",
            data: {json : json},
            cache: false,
            beforeSend: function() {
                // setting a timeout
                //запрещаем повторное нажатие кнопки после отправки
                $('#add-checkout-form').find('button').prop('disabled', true);
                $('#wait').removeClass('hide');
            },
            success: function (response) {
                //console.log(response);
                if(response == 1){
                    setTimeout(function () {
                        window.location.reload()
                    }, 3000);
                } else if(response == 0){
                    $('.error_form_purchases').text('Request sending error')
                }
            }
        });
        return false;
        //$('#add-checkout-modal').foundation('close');
    }
});

// Показываем в модальном окне, продукты заказа
$(document).on('dblclick', '.checkout tbody tr', function(e) {
    var order_id = $(this).attr('data-order-id');

    $.ajax({
        url: "/adm/crm/show_orders",
        type: "POST",
        data: {order_id : order_id},
        cache: false,
        success: function (response) {
            //alert(response);
            $('#show-details').foundation('open');
            $('#container-details').html(response);
        }
    });

    return false;
});

// Показываем форму для отправки файла
$('#add-order-file').click(function(){
    $('.purchase-file-send').slideToggle();
});

// Запрещаем повторно отправлять заявку
$('#orders-excel-send').submit(function(e) {
    e.preventDefault();
    $('#orders-excel-send').find('button').prop('disabled', true);
    $('#wait').removeClass('hide');
    setTimeout(function () {
        e.target.submit()
    }, 3000);
});

$('table').tablesort();

// Подтверждение заказов
$(document).on('click', '.order-accept', function(e) {
    e.preventDefault();
    var self = $(this);
    var order_id = self.parents('td').parents('tr').attr('data-order-id');
    $('#goods_data').find('tr').removeClass("blue");

    $.ajax({
        url: "/adm/crm/orders_action",
        type: "POST",
        data: {order_id : order_id, action : 'accept'},
        cache: false,
        success: function (response) {
            console.log((response));
            var obj = JSON.parse(response);
            if(obj.ok == 1){
                self.parents('td').parents('tr').addClass("blue");
                self.parents('td').parents('tr').find('td').eq(7).removeClass().addClass(obj.class).text(obj.text);
                self.parents('td').find('a').remove();
            } else {
                alert(obj.error);
            }
        }
    });
    return false;
});

// Отказ заказа
$(document).on('click', '.order-dismiss', function(e) {
    e.preventDefault();
    $('.dismiss-container').remove();
    var self = $(this);
    var order_id = self.parents('td').parents('tr').attr('data-order-id');
    $('#goods_data').find('tr').removeClass("blue");
    self.parents('td').parents('tr').addClass("blue");
    // Добавляем блок с комментариями
    var html = "<div class='dismiss-container'>" +
             "<textarea class='dismiss-comment' cols='30' placeholder='Комментарий' rows='3'></textarea>" +
            "<button id='send-dismiss'>Отказ</button>" +
            "<button id='send-close'>Закрыть</button>" +
            "</div>";
    self.after(html);

    // Удаляем блок с комментариями
    $('#send-close').click(function () {
        $('.dismiss-container').remove();
        self.parents('td').parents('tr').removeClass("blue");
    });

    $('#send-dismiss').click(function () {
        var comment = $('.dismiss-comment').val();
        //alert(comment);
        $.ajax({
            url: "/adm/crm/orders_action",
            type: "POST",
            data: {order_id : order_id, action : 'dismiss', comment : comment},
            cache: false,
            success: function (response) {
                console.log((response));
                var obj = JSON.parse(response);
                if(obj.ok == 1){
                    self.parents('td').parents('tr').find('td').eq(7).removeClass().addClass(obj.class).text(obj.text);
                    self.parents('td').find('a').remove();
                    $('.dismiss-container').remove();
                } else {
                    alert(obj.error);
                }
            }
        });
        return false;
    });
});


// Возврат заказа в реквест
$(document).on('click', '.order-return', function(e) {
    e.preventDefault();
    var self = $(this);
    var request_id = self.attr('data-request-id');
    var order_id = self.parents('td').parents('tr').attr('data-order-id');
    $('#goods_data').find('tr').removeClass("blue");

    $.ajax({
        url: "/adm/crm/orders_action",
        type: "POST",
        data: {request_id : request_id, action : 'return'},
        cache: false,
        success: function (response) {
            console.log((response));
            var obj = JSON.parse(response);
            if(obj.ok == 1){
                self.parents('td').parents('tr').addClass("blue");
                self.parents('td').parents('tr').find('td').eq(7).removeClass().addClass(obj.class).text(obj.text);
                self.parents('td').find('a').remove();
            } else {
                alert(obj.error);
            }
        }
    });
    return false;
});