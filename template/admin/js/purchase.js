// ОТкрываем модальное окно для выбора даты експорта
$(document).on('click', '#export-button', function(e) {
    $('#export-modal form')[0].reset();
    $('#export-modal').foundation('open');
});
// открыть окно добавления корзины
$('body').on('click', '#add-checkout-button', function() {
    $('#add-checkout-modal').foundation('open');
    hideAllInput();
});

// $(document).on('change', '#id_partner', function(e){
    // alert(e.target.value)
// });

//PART NUMBER SEARCH
$('[name="part_number"]').keyup(function(e) {
    //ajax e.target.value
	$('[type="submit"]').removeAttr('disabled');
	var stock = $('#stock').val();
    var id_partner = $('#id_partner').val();
    var part_number = e.target.value;

    $.ajax({
        url: "/adm/crm/purchase_part_num_ajax",
        type: "POST",
        data: {id_partner : id_partner, part_number : part_number, stock : stock},
        cache: false,
        success: function (response) {
            var obj = JSON.parse(response);
            //console.log(obj);
            if(obj.result == 1 && obj.action == 'stock'){
                // нашли на складах
			
                $('.result_stock').html('This Part has been found on the <span style="color: #4CAF50">' + obj.stock_name + '</span>. Please, make an order.');
                //console.log(obj.mName + ' ' + obj.stock_name);
				$('[type="submit"]').attr('disabled','true');
				
				$("[name='part_number']").removeClass('error_part');
                $('.name-product').text(obj.mName).css('color', '#4CAF50');
                
            } else if(obj.result == 1 && obj.action == 'purchase'){
                // не нашли на складанах но парт номер есть в базе, покупаем товар
                //console.log(obj.mName);
                $('.name-product').text(obj.mName).css('color', '#4CAF50');
                $("[name='part_number']").removeClass('error_part');
		
                
            } else if(obj.result == 0 && obj.action == 'not_found'){
                // не нашли парт номер
                //console.log('не нашли партномер');
                $("[name='part_number']").addClass('error_part');
                $('.name-product').text('not found').css('color', 'red');
				$('.result_stock').text('');
            }

            //if(response == 0){
            //    $("[name='part_number']").addClass('error_part');
            //    $('.name-product').text('not found').css('color', 'red');
            //} else {
            //    //console.log(obj[0].mName);
            //    $('.name-product').text(response).css('color', '#4CAF50');
            //    $("[name='part_number']").removeClass('error_part');
            //}
        }
    });

    return false;
});

$('#id_partner').change(function(e){
    hideAllInput();
    $('#stock').val(0).change();
});


function hideAllInput() {
    $('[name="service_order"], [name="part_number"], [name="price"], [name="quantity"]')
        .removeAttr('required')
        .parent().hide();
    $('.name-product').text('');
	$('.result_stock').text('');
	$('.error_form_purchases').text('');
	$('[name="part_number"]').val('');
	$('[type="submit"]').removeAttr('disabled');
}
$('#stock').change(function(e) {
    switch (e.target.value) {
        case 'OK (Выборгская, 104)':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            break;
        case 'OK':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            break;
        case 'BAD':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            break;
        case 'Local Source':
            hideAllInput();
            $('[name="service_order"], [name="part_number"], [name="price"]')
                .attr('required', '')
                .parent().show();
            break;
        case 'Not Used':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            break;
        case 'ITC Warranty':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            break;
        case 'Restored':
            hideAllInput();
            $('[name="part_number"], [name="quantity"]')
                .attr('required', '')
                .parent().show();
            break;
        case 'Restored Bad':
            hideAllInput();
            $('[name="part_number"], [name="quantity"]')
                .attr('required', '')
                .parent().show();
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
        newCheckout.goods_name = $('.name-product').text();
        //CHECKOUTS.push(newCheckout); // TEST
        var json = JSON.stringify(newCheckout); // json
        $.ajax({
            url: "/adm/crm/purchase_ajax",
            type: "POST",
            data: {json : json},
            cache: false,
            beforeSend: function() {
                // setting a timeout
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
                    $('.error_form_purchases').text('Request sending error');
                }
            }
        });
        return false;
        //$('#add-checkout-modal').foundation('close');
    }
    //e.preventDefault();
});


// Запрещаем вводить кол-во больше назначеного
$(document).on('keyup', '[name="quantity"]', function(e) {
    if (e.target.value > 50) { e.target.value = 50 }
    if (e.target.value < 1) { e.target.value = 1 }
});


// Показываем форму для отправки файла
$('#add-checkout-file').click(function(){
   $('.purchase-file-send').slideToggle();
});


// Запрещаем повторно отправлять заявку
$('#purchase-excel-send').submit(function(e) {
    e.preventDefault();
    $('#purchase-excel-send').find('button').prop('disabled', true);
    $('#wait').removeClass('hide');
    setTimeout(function () {
        e.target.submit()
    }, 3000);
});

// Показываем в модальном окне, продукты покупки
$(document).on('dblclick', '.checkout tbody tr', function(e) {
    var site_id = $(this).attr('data-siteid');

    $.ajax({
        url: "/adm/crm/show_purchses",
        type: "POST",
        data: {site_id : site_id},
        cache: false,
        success: function (response) {
            $('#show-details').foundation('open');
            $('#container-details').html(response);
        }
    });
    return false;
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

$('table').tablesort();