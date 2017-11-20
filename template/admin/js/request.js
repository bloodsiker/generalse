
$('table').tablesort();

// открыть окно добавления корзины
$('body').on('click', '#add-request-button', function() {
    $('.name-product').text('');
    $('.pn-analog').text('');
    $(".group-stocks").addClass('hide');
    $(".group-analog").addClass('hide');
    $('#add-request-modal form')[0].reset();
    $('#add-request-modal').foundation('open');
});

$('body').on('click', '#add-multi-request-button', function() {
    $('.name-product').text('');
    $('.pn-analog').text('');
    $(".group-stocks").addClass('hide');
    $(".group-analog").addClass('hide');
    $('#add-multi-request-modal form')[0].reset();
    $('#add-multi-request-modal').foundation('open');
});

// модальное окно (узнать цену по парт номеру)
$('body').on('click', '#price-button', function() {
    $('.name-product').text('');
    $('.pn-analog').text('');
    $(".group-stocks").addClass('hide');
    $(".group-analog").addClass('hide');
    $('#price-modal form')[0].reset();
    $('#price-modal').foundation('open');
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


// Запрещаем вводить в поле все кроме цифт
function checkCurrPartNumber(d) {
    if(window.event)
    {
        if(event.keyCode == 37 || event.keyCode == 39) return;
    }
    d.value = d.value.replace(/\D/g,'');
}

// Запрещаем вводить количество меньше 1
function validCount(e) {
    if(e.value < 1) { e.value = 1 }
    return e.value * 1 == e.value
}


// Поиск по чекбоксам
let checkedArr = [];
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


//PART NUMBER SEARCH
$('[name="part_number"]').keyup(function(e) {
    //ajax e.target.value
    var part_number = e.target.value;

    $.ajax({
        url: "/adm/crm/request/price_part_ajax",
        type: "POST",
        data: {action : 'part-price', part_number : part_number},
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

                if(obj.is_available == 1){
                    $('.pn-analog').text(obj.comment);
                    $("[name='part_number']").addClass('error_part');
                } else {
                    $('.pn-analog').text('');
                    $("[name='part_number']").removeClass('error_part');

                    if(obj.is_analog == 1){
                        $('.pn-analog').text(obj.message + obj.analog);
                        $("[name='part-analog']").val(obj.analog);
                        $("[name='analog-price']").val(obj.analog_price);
                        $('.group-analog').removeClass('hide');
                    } else {
                        $('.pn-analog').text('');
                        $("[name='part-analog']").val('');
                        $("[name='analog-price']").val('');
                        $(".group-analog").addClass('hide');
                    }
                }
            } else {
                $("[name='part_number']").removeClass('error_part');
                $('.name-product').text('not found').css('color', 'red');
                $("[name='price']").val('0');
                $(".group-stocks").addClass('hide');
                $('.name-stock').text('');
                $("[name='quantity']").val('');
                $('.pn-analog').text('');
                $("[name='part-analog']").val('');
                $("[name='analog-price']").val('');
                $(".group-analog").addClass('hide');
            }
        }
    });
    return false;
});

// Показываем скрытый инпут в зависимости от выбраного значения
$(document).on('change', 'select[name="note"]', function (e) {
    if(e.target.value == 'other_address'){
        $('input[name="your_address"]').attr('type', 'text').attr('required', 'required').addClass('required');
    } else {
        $('input[name="your_address"]').attr('type', 'hidden').removeAttr('required').removeClass('required');
    }
});



// Мульти реквест
let multiPartNumber = function (e) {
    $('#load_part_number').html('<i class="fa fa-spin fa-spinner"></i>');
    let part_number = $("#add-multi-request-form [name='multi_part_number']").val();
    let quantity = $("#add-multi-request-form [name='part_quantity']").val();
    if(validCount({value:quantity})){


        $.ajax({
            url: "/adm/crm/request/price_part_ajax",
            type: "POST",
            data: {action : 'part-stock', part_number : part_number, quantity : quantity},
            cache: false,
            success: function (response) {
                console.log(response);
                let obj = JSON.parse(response);
                console.log(obj);


                let notStockFunc = () => {
                    $('[name="stock_name"]').val('');
                    $('[name="stock_price"]').val('');
                    $('[name="stock_count"]').val('0');
                    $('[name="pn_price"]').val('0');
                    $("#add-multi-request-form [name='goods_name']").val(obj.goods_name[0]);
                    $('.stocks-view').html('<li>Не найдено на складах</li>');
                    $("[name='stock_id']").html('<option value="">ЗАПРОС НА ПОСТАВКУ</option>');
                    $('[name="stock_name"]').val('ЗАПРОС НА ПОСТАВКУ');
                };

                if(obj.status == 200){

                    $("#add-multi-request-form [name='goods_name']").val(obj.goods_name[0]);
                    let arr = [];
                    console.log(obj.stocks);

                    let parts_elems = $('#cart-container').find('.cart-part-number');
                    let stocks_elems = $('#cart-container').find('.cart-stock');
                    let counts_elems = $('#cart-container').find('.cart-count');

                    let cart_products = [];
                    for (let i = 0; i < parts_elems.length; i++) {
                        if (part_number === parts_elems[i].innerHTML) {
                            cart_products.push({
                                part_number: parts_elems[i].innerHTML,
                                stock: stocks_elems[i].innerHTML,
                                count: counts_elems[i].innerHTML,
                            })
                        }
                    }


                    if (cart_products.length) {
                        Object.keys(obj.stocks).forEach(key => {
                            //let count = +obj.stocks[key].quantity > +quantity ? quantity : obj.stocks[key].quantity;
                            let count = +obj.stocks[key].quantity;
                            console.log(count);

                            cart_products.forEach(product => {
                                if (product.stock === key) {
                                    count = count - product.count;

                                }
                            });

                            if (count > 0) {
                                arr.push({
                                    title: key,
                                    count: count,
                                    stock_id: obj.stocks[key].stock_id,
                                    price: (+obj.stocks[key].price).toFixed(2)
                                });
                            }

                        });
                    } else {
                        Object.keys(obj.stocks).forEach(key => {
                            let count = +obj.stocks[key].quantity > +quantity ? quantity : obj.stocks[key].quantity;

                            arr.push({
                                title: key,
                                count: count,
                                stock_id: obj.stocks[key].stock_id,
                                price: (+obj.stocks[key].price).toFixed(2)
                            });
                        });
                    }

                    if (arr.length) {
                        $('[name="stock_count"]').val('');
                        $('[name="stock_name"]').val('');
                        $('[name="stock_price"]').val('');
                        $('[name="pn_price"]').val('');
                        $('.stocks-view').html('');
                        $('.stocks-view').html('<li style="color: #09c509;">Найдено на складах:</li>');
                        $("[name='stock_id']").html('<option value=""></option>');
                        arr.forEach(key => $('.stocks-view').append('<li>' +key.title +' - ' +key.count +'шт</li>'));
                        arr.forEach(key => $("select[name='stock_id']")
                            .append('<option data-count="'+key.count+'" data-price="'+key.price+'" data-stock-name="'+key.title+'" value="' + key.stock_id +'">' +key.title +' - ' +key.count +'шт</option>'));

                    } else {
                        notStockFunc();
                    }

                } else {
                    notStockFunc();
                }
                $('#load_part_number').html('');

            }
        });

        $(document).on('change', '[name="stock_id"]', function (e) {
            let count = $(this).find('option:selected').attr('data-count');
            let stock_name = $(this).find('option:selected').attr('data-stock-name');
            let stock_price = $(this).find('option:selected').attr('data-price');
            $('[name="stock_count"]').val(count);
            $('[name="stock_name"]').val(stock_name);
            $('[name="stock_price"]').val(stock_price);
            $('[name="pn_price"]').val(stock_price);
        })
    }

};
$("#add-multi-request-form [name='multi_part_number'], #add-multi-request-form [name='part_quantity']").keyup(function(e) {
    multiPartNumber(e);
});


// Востановляем реквест
$(document).on('click', '.restored', function (e) {
    let _this = $(this);
    let id_request = _this.attr('data-reqid');

    $('.dismiss-container').remove();
    let html = "<div class='dismiss-container'>" +
        "<input type='number' class='push-input pull-left' name='new-period' style='width: 35%; height: 30px; color: #0a0a0a; padding-left: 10px; margin: 0' placeholder='Period'>" +
        "<div class='pull-right' style='width: 65%'>" +
        "<button id='send-restore'>Restore</button>" +
        "<button id='send-close'>Close</button>" +
        "</div>" +
        "</div>";
    _this.after(html);

    // Удаляем блок с комментариями
    $('#send-close').click(function () {
        $('.dismiss-container').remove();
    });

    $('#send-restore').click(function () {
        let period = $('[name="new-period"]').val();
        console.log(period);
        $.ajax({
            url: "/adm/crm/request/request_ajax",
            type: "POST",
            data: {period : period, action : 'restore_request', id_request : id_request},
            cache: false,
            success: function (response) {
                if(response == 200){
                    _this.parent('td').parent('tr').fadeOut(700, function () {
                        _this.parent('td').parent('tr').remove();
                    });
                } else {
                    alert('Error');
                }
            }
        });
        return false;
    });
});

// Добавляем в корзину
$('#add-multi-request-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-multi-request-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        e.preventDefault();

        let data = $('#add-multi-request-form').serialize();
        data +=data + '&action=save_to_cart';
        console.log(data);
        $.ajax({
            url: "/adm/crm/request/request_ajax",
            type: "POST",
            data: data,
            cache: false,
            success: function (response) {
                $('#cart-container').html('');
                $('#cart-container').html(response);
                $('#add-multi-request-modal form')[0].reset();
                $('.stocks-view').html('');
            }
        });
        return false;

        // $('#add-request-form').find('button').prop('disabled', true);
        // $('#wait').removeClass('hide');
        // setTimeout(function () {
        //     e.target.submit()
        // }, 2000);
    }
});

// Чистим корзину в multi-request
$(document).on('click', '#clear-multi-cart', function (e) {
    e.preventDefault();
    $.ajax({
        url: "/adm/crm/request/request_ajax",
        type: "POST",
        data: {action : 'clear_multi_cart'},
        cache: false,
        success: function (response) {
            $('#cart-container').html('');
            $('#cart-container').html(response);
        }
    });
    return false;
});

// Удаляем елемент с корзины
$(document).on('click', '.delete-request-with-cart', function (e) {
    e.preventDefault();
    let id_trash = $(this).attr('data-trash-id');
    $.ajax({
        url: "/adm/crm/request/request_ajax",
        type: "POST",
        data: {id_trash : id_trash, action : 'delete_element_multi_cart'},
        cache: false,
        success: function (response) {
            $('#cart-container').html('');
            $('#cart-container').html(response);
        }
    });
    return false;
});


$(document).on('click', '#send-multi-cart', function (e) {
    e.preventDefault();
    $.ajax({
        url: "/adm/crm/request/request_ajax",
        type: "POST",
        data: {action : 'send-multi-request'},
        cache: false,
        success: function (response) {
            console.log(response);
            $('#wait').removeClass('hide');
            if(response == 200){
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            } else {
                alert('Error sending request');
                $('#wait').addClass('hide');
            }
        }
    });
    return false;
});

// Показываем в модальном окне, продукты заказа
$(document).on('dblclick', '.checkout tbody tr', function(e) {
    let number = $(this).attr('data-number');
    if(number){
        console.log(number);
        $.ajax({
            url: "/adm/crm/request/request_ajax",
            type: "POST",
            data: {number : number, action : 'show-multi-request'},
            cache: false,
            success: function (response) {
                //alert(response);
                $('#show-details').foundation('open');
                $('#container-details').html(response);
            }
        });
        return false;
    }
});


// Удаляем реквест
$(document).on('click', '.delete-request', function (e) {
    let _this = $(this);
    let id_request = _this.attr('data-reqid');

    $.ajax({
        url: "/adm/crm/request/request_ajax",
        type: "POST",
        data: {action : 'delete_request', id_request : id_request},
        cache: false,
        success: function (response) {
            if(response == 200){
                _this.parent('td').parent('tr').fadeOut(700, function () {
                    _this.parent('td').parent('tr').remove();
                });
            } else {
                alert('Error');
            }
        }
    });
    return false;
});



// Отправляем заявку на реквест
$('#add-request-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-request-form input').hasClass('is-invalid-input')
        || $('#add-request-form select').hasClass('is-invalid-input')
        || $('#add-request-form input').hasClass('error_part')) { // проверка на валидность
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
            console.log(xhr);
            status.html(xhr.responseText);
            //status.html('Файл успешно загружен!');
        }
    });

})();