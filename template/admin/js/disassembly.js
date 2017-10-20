// ОТкрываем модальное окно для выбора даты експорта
$(document).on('click', '#export-button', function(e) {
    $('#export-modal form')[0].reset();
    $('#export-modal').foundation('open');
});

$('#send-form').attr('disabled', '');
$('.checkout').after();

var note;
var count;

var checkNote = false;
$(document).on('keyup', '#note', function (e) {
    checkNote = e.target.value.length > 2;
    note = e.target.value;
    showButton();
});

function showButton() {
    count = 0;
    $('.checked-label').each(function(index, el) {
        count++;
        //console.log(count);
        if (count > 4 && checkNote) {
            $('#send-form').removeAttr('disabled');
        } else {
            $('#send-form').attr('disabled', '');
        }
    });
}

$(document).on('click', '.checkbox', function(e) {

    if ($(this).is(':checked')) { // CHECKED
        $(this).parent().addClass('checked-label');
        $(this).parents('tr').attr('checked', 'true');

    } else {
        $(this).parent().removeClass('checked-label');
        $(this).parents('tr').removeAttr('checked').removeAttr('error');
    }
    showButton();
});

// Получаем id партнера из селекта
var id_partner = $('[name="id_partner"]').val();

$(document).on('change', '[name="id_partner"]', function(e) {
	$('#result_disassembly').remove();
	$('#count_rows_info').text('');
	$('.search-input').val('');
	$('#send-form').attr('disabled', '');
    id_partner = e.target.value;
	//alert(id_partner);
});
$(document).on('click', '#send-form', function(e) {
    var data = [];
    var sendData = false;
    $('tr[checked]').each(function(index, el) {
        var newData = new Object();
		
        var sn = $(this).attr('data-sn');
        var dev_pn = $(this).attr('data-pn');
        var dev_name = $(this).attr('data-name');
        var stock_name = $(this).attr('data-stock');
        var pn = $(el).children('td').eq(1).html();
        var desc = $(el).children('td').eq(2).html();
        var stock = $(el).children('td').eq(3).children('select').val();
        var qua = $(el).children('td').eq(4).children('input').val();
		newData.id_partner = id_partner;
		newData.note = note;
        newData.pn = pn;
        newData.desc = desc;
        newData.stock = stock;
        newData.qua = qua;
        newData.sn = sn;
        newData.dev_pn = dev_pn;
        newData.dev_name = dev_name;
        newData.stock_name = stock_name;
        if (stock != null) {
            $(el).removeAttr('error');
        } else {
            $(el).attr('error', 'true');
        }
        if ($(el).attr('error') !== 'true') {

            data.push(newData);
        }
    });
    if (data.length == count) {
        var json = JSON.stringify(data);
        $(document).find('#send-form').prop('disabled', true);
        //console.log(json);
        $.ajax({
            url: "/adm/crm/disassembly_ajax",
            type: "POST",
            data: {json : json},
            cache: false,
            beforeSend: function() {
                // setting a timeout
                $('#wait').removeClass('hide');
            },
            success: function (response) {
                //console.log(response);
                setTimeout(function () {
                    $('#wait').addClass('hide');
                    $('#send-form, #result_disassembly').remove();
                    $('.search-input').val('');

                    var html_ok = "<div class='thank_you_page'>" +
                        "<h3>Thank you, your request has been sent</h3>" +
                        "</div>";
                    var html_error = "<div class='thank_you_page'>" +
                        "<h3>Error sending a request,<br> please contact our managers</h3>" +
                        "</div>";

                    if(response == 1){
                        $('.checkout').html(html_ok);
                    } else {
                        $('.checkout').html(html_error);
                    }
                }, 3000);

            },
            error: function(){
                $('#send-form, #result_disassembly').remove();
                $('#count_rows_info').text('Error sending a request, please contact our managers');
                $('.search-input').val('');
            }
        });

        return false;
    }
});

$(document).on('keyup', '[type="number"]', function(e) {
    if (e.target.value > 5) { e.target.value = 5 }
    if (e.target.value < 1) { e.target.value = 1 }
});

// Показываем в модальном окне, продукты от разбора
$(document).on('dblclick', '.checkout tbody tr', function(e) {
    var site_id = $(this).attr('data-siteid');

    $.ajax({
        url: "/adm/crm/show_disassembly",
        type: "POST",
        data: {site_id : site_id},
        cache: false,
        success: function (response) {
            //alert(response);
            $('#show-details').foundation('open');
            $('#container-details').html(response);
        }
    });

    return false;
});


// Подтверждение заказов
$(document).on('click', '.disassemble-accept', function(e) {
    e.preventDefault();
    var self = $(this);
    var decompile_id = self.parents('td').parents('tr').attr('data-decompile');
    $('#goods_data').find('tr').removeClass("blue");

    $.ajax({
        url: "/adm/crm/disassembly_action_ajax",
        type: "POST",
        data: {decompile_id : decompile_id, action : 'accept'},
        cache: false,
        success: function (response) {
            console.log((response));
            var obj = JSON.parse(response);
            if(obj.ok == 1){
                self.parents('td').parents('tr').addClass("blue");
                self.parents('td').parents('tr').find('td').eq(6).removeClass().addClass(obj.class).text(obj.text);
                self.parents('td').find('a').remove();
            } else {
                alert(obj.error);
            }
        }
    });
    return false;
});

// Отказ заказа
$(document).on('click', '.disassemble-dismiss', function(e) {
    e.preventDefault();
    $('.dismiss-container').remove();
    var self = $(this);
    var decompile_id = self.parents('td').parents('tr').attr('data-decompile');
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
            url: "/adm/crm/disassembly_action_ajax",
            type: "POST",
            data: {decompile_id : decompile_id, action : 'dismiss', comment : comment},
            cache: false,
            success: function (response) {
                console.log((response));
                var obj = JSON.parse(response);
                if(obj.ok == 1){
                    self.parents('td').parents('tr').find('td').eq(6).removeClass().addClass(obj.class).text(obj.text);
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


// Подтверждение заказов
$(document).on('click', '.disassemble-delete', function(e) {
    e.preventDefault();
    var self = $(this);
    var site_id = self.parents('td').parents('tr').attr('data-siteid');
    $('#goods_data').find('tr').removeClass("blue");
    var count_request = $('#count_refund').text();

    $.ajax({
        url: "/adm/crm/disassembly_action_ajax",
        type: "POST",
        data: {site_id : site_id, action : 'delete'},
        cache: false,
        success: function (response) {
            console.log((response));
            var obj = JSON.parse(response);
            if(obj.ok == 1){
                window.location.reload();
                // self.parents('td').parents('tr').remove();
                // var table = document.getElementById('goods_data');//получаем элемент таблицы
                // var count = table.rows.length;//количество строк
                // $('#count_refund').html("(" + (count - 1) + ")");
            } else {
                alert(obj.error);
            }
        }
    });
    return false;
});
