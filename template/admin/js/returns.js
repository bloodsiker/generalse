// ОТкрываем модальное окно для выбора даты експорта
$(document).on('click', '#export-button', function(e) {
    $('#export-modal form')[0].reset();
    $('#export-modal').foundation('open');
});

// Показываем форму для отправки файла
$('#add-return-file').click(function(){
    $('.purchase-file-send').slideToggle();
});

$('table').tablesort();

$('.apply-cout').attr('disabled', '');

$(document).on('change', '[name="stock"]', function (event) {
    if (event.target.value != null) {
        $(this).parents('tr').find('.apply-cout').removeAttr('disabled');
    }
});

// подтвердить корзину
$('body').on('click', '.apply-cout', function(e) {
    var parents = $(this).parents('tr');
    var dataReturn = parents.attr('data-return');
    var answer = confirm("Apply checkout, service order: " + dataReturn + " ?");
    if (answer) {
        var stock = parents.find('[name="stock"]').val();
        var action = 'update';
        //var json = JSON.stringify(); // json
        //console.log(stock + ' - ' + dataReturn); // send to server json

        $.ajax({
            url: "/adm/crm/returns_ajax",
            type: "POST",
            data: {stock : stock, id_return : dataReturn, action : action},
            cache: false,
            success: function (response) {
                //alert(response);
                console.log(response);
                var obj = JSON.parse(response);
                if(obj.status == 'ok'){
                    parents.find('.status_return').text('В обработке');
                    parents.find('.status_return').removeClass('yellow').addClass('green');
                    parents.find('button').remove();
                    parents.find('[name="stock"]').remove();
                    parents.find('.selectInTable').addClass('stock').removeClass('selectInTable');
                    parents.find('.stock').text(obj.stock);
                }
            }
        });

    } else {
        return false;
    }
});


// Подтверждение заказов
$(document).on('click', '.return-accept', function(e) {
    e.preventDefault();
    var self = $(this);
    var return_id = self.parents('td').parents('tr').attr('data-return');
    $('#goods_data').find('tr').removeClass("blue");

    $.ajax({
        url: "/adm/crm/returns_ajax",
        type: "POST",
        data: {return_id : return_id, action : 'accept'},
        cache: false,
        success: function (response) {
            console.log((response));
            var obj = JSON.parse(response);
            if(obj.ok == 1){
                self.parents('td').parents('tr').addClass("blue");
                self.parents('td').parents('tr').find('td').eq(8).removeClass().addClass(obj.class).text(obj.text);
                self.parents('td').find('a').remove();
            } else {
                alert(obj.error);
            }
        }
    });
    return false;
});

// Отказ заказа
$(document).on('click', '.return-dismiss', function(e) {
    e.preventDefault();
    $('.dismiss-container').remove();
    var self = $(this);
    var return_id = self.parents('td').parents('tr').attr('data-return');
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
            url: "/adm/crm/returns_ajax",
            type: "POST",
            data: {return_id : return_id, action : 'dismiss', comment : comment},
            cache: false,
            success: function (response) {
                console.log((response));
                var obj = JSON.parse(response);
                if(obj.ok == 1){
                    self.parents('td').parents('tr').find('td').eq(8).removeClass().addClass(obj.class).text(obj.text);
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

// Запрещаем повторно отправлять заявку
$('#return-excel-send').submit(function(e) {
    e.preventDefault();
    $('#return-excel-send').find('button').prop('disabled', true);
    $('#wait').removeClass('hide');
    setTimeout(function () {
        e.target.submit()
    }, 3000);
});
