// ОТкрываем модальное окно для выбора даты експорта
$(document).on('click', '#export-button', function(e) {
    $('#export-modal form')[0].reset();
    $('#export-modal').foundation('open');
});

// Показываем форму для отправки файла
$('#add-return-file').click(function(){
    $('.purchase-file-send').slideToggle();
});

//$('table').tablesort();

$('.apply-cout').attr('disabled', '');

$(document).on('change', '[name="stock"]', function (event) {
    if (event.target.value != null) {
        $(this).parents('tr').find('.apply-cout').removeAttr('disabled');
    }
});

// подтвердить корзину
let dataReturn = null;
$('.umbrella-table').on('click', '.apply-cout', function(e) {
    var parents = $(this).parents('tr');
    dataReturn = parents.attr('data-return');
    $('#note-modal').foundation('open');
});

$('#send-return').click(function() {
    var note = $('#note').val();
    var _this = $('[data-return="' + dataReturn + '"]');

    let stock = _this.find('[name="stock"]').val();

    $.ajax({
        url: "/adm/crm/returns_ajax",
        type: "POST",
        data: {stock : stock, id_return : dataReturn, action : 'update', note : note},
        cache: false,
        success: function (response) {
            $('#note-modal').foundation('close');
            var obj = JSON.parse(response);
            if(obj.status == 'ok'){
                if(note != ''){
                    var tooltip = '<i class="fi-info tool-tip has-tip [tip-top]" data-tooltip title="' + note + '"></i>';
                    _this.find('.return-note').html(tooltip);
                }
                _this.find('.status_return').text('В обработке');
                _this.find('.status_return').removeClass('yellow').addClass('green');
                _this.find('button').remove();
                _this.find('[name="stock"]').remove();
                _this.find('.selectInTable').addClass('stock').removeClass('selectInTable');
                _this.find('.stock').text(obj.stock);
                $('#note').val('');
                showNotification('Return successfully created', 'success');
            } else {
                showNotification('Could not return, contact the manager', 'error');
            }
        }
    });
});

// При открытии модального окна для загрузки файла, цепляем id записи
$('.umbrella-table').on('click', '[data-open="open-upload-return"]', function () {
    var parents = $(this).parents('tr');
    var return_id = parents.attr('data-return');
    $('form#return-upload').find('[name="return_id"]').val(return_id);
    showUploadDocumentInReturn(return_id);
});

// Send return document
$("#return-upload").submit(function(e){
    e.preventDefault();

    let $that = $(this),
        formData = new FormData($that.get(0));

    $.ajax({
        url: "/adm/crm/returns_upload",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            if(obj.ok == 1){
                $('#open-upload-return').foundation('close');
                $('form#return-upload').trigger('reset');
                $('[data-return="' + obj.return_id + '"]').find('.return-document').removeClass('red blue').addClass('blue');
                showNotification(obj.text, obj.type);
            } else {
                showNotification(obj.text, obj.type);
            }
        }
    });
    return false;
});

// Show upload document
let showUploadDocumentInReturn = (return_id) => {
    $('form#return-upload').find('.container-upload-file').html('');
    $.ajax({
        url: "/adm/crm/returns_ajax",
        type: "POST",
        data: {return_id : return_id, action : 'show_document'},
        cache: false,
        success: function (response) {
            console.log(response);
            $('form#return-upload').find('.container-upload-file').html(response);
        }
    });
    return false;
};


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
                self.parents('td').parents('tr').find('td.status_return').removeClass().addClass(obj.class).text(obj.text);
                self.parents('td').find('a').remove();
                showNotification(obj.success, 'success');
            } else {
                showNotification(obj.error, 'error');
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
                    self.parents('td').parents('tr').find('td.status_return').removeClass().addClass(obj.class).text(obj.text);
                    self.parents('td').find('a').remove();
                    $('.dismiss-container').remove();
                    showNotification(obj.success, 'success');
                } else {
                    showNotification(obj.error, 'error');
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
