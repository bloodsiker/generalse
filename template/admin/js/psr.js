$(document).on('click', '#add-psr', function(e) {
    $('#add-psr-modal form')[0].reset();
    $('#add-psr-modal').foundation('open');
});

$(document).on('click', '#add-psr-dec', function(e) {
    $('#add-dec-number-modal form')[0].reset();
    $('#add-dec-number-modal').foundation('open');
});



//PART NUMBER SEARCH
$('[name="mtm"]').keyup(function(e) {
    //ajax e.target.value
    var part_number = e.target.value;

    $.ajax({
        url: "/adm/psr/psr_ajax",
        type: "POST",
        data: {part_number : part_number, action : 's_part_number'},
        cache: false,
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            if(obj.result == 1){
                $("[name='mtm']").removeClass('error_part');
                $('.name-product').text(obj.mName).css('color', '#4CAF50');
            } else {
                $("[name='mtm']").addClass('error_part');
                $('.name-product').text(obj.mName).css('color', 'red');
            }
        }
    });
    return false;
});



var startDate = 0, endDate = 0;
$(document).on('change', '[name="manufacture_date"]', function(event) {
    startDate = new Date(event.target.value);
    dateRez();
});
$(document).on('change', '[name="purchase_date"]', function(event) {
    endDate = new Date(event.target.value);
    dateRez();
});


function dateRez() {
    if (startDate == 0 || endDate == 0) {
        return false;
    } else {
        var days = ((endDate - startDate)/86400)/1000;
        if (days >= 365 && days <= 730) {
            $('.error-date').hide();
            $('[name="Days"]').val(days).removeClass('error_psr');
        } else {
            $('.error-date').show();
            $('[name="Days"]').val(days).addClass('error_psr');

        }
    }
}


$(document).on('keydown', '[name="Days"]', function() {
    return false;
});

// Отправляем форму регистрации ПСР
$('#add-psr-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-psr-form input').hasClass('is-invalid-input')
        || $('#add-psr-form input').hasClass('error_psr')
        || $('#add-psr-form input').hasClass('error_part')) { // проверка на валидность
        return false;
    } else {
        $('#add-psr-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 2000);
    }
});

// При открытии модального окна для загрузки файла, цепляем id записи
$(document).on('click', '[data-open="open-upload-psr"]', function () {
   var psr_id = $(this).attr('data-psr-id');
   $('form#psr-upload').find('[name="psr_id"]').val(psr_id);

    $.ajax({
        url: "/adm/psr/show_upload_file",
        type: "POST",
        data: {psr_id : psr_id},
        cache: false,
        success: function (response) {
            console.log(response);
            $('form#psr-upload').find('.container-upload-file').html(response);
        }
    });
    return false;
});

// Отправляем форму если проходит валидацию
$('#psr-upload').submit(function(e) {
    e.preventDefault();
    if ($('#psr-upload input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#psr-upload').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 2000);
    }
});


// Ищем ПСР по ID
$('[name="psr_id"]').keyup(function(e) {
    //ajax e.target.value
    var id_psr = e.target.value;

    $.ajax({
        url: "/adm/psr/psr_ajax",
        type: "POST",
        data: {id_psr : id_psr, action : 'add_psr_dec'},
        cache: false,
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            var _form = $('form#add-dec-number-form');
            if(response != false){
                _form.find("[name='mtm']").removeClass('error_part').val(obj.part_number);
                _form.find("[name='serial_number']").removeClass('error_part').val(obj.serial_number);
                _form.find("[name='device_name']").removeClass('error_part').val(obj.device_name);
                if(obj.declaration_number == ''){
                    _form.find("[name='declaration_number']").removeClass('error_part').val(obj.declaration_number)
                        .prop('disabled', false).removeAttr('style');
                } else {
                    _form.find("[name='declaration_number']").addClass('error_part').val(obj.declaration_number)
                        .attr('disabled', true).css({'background-color' : 'rgba(198,15,19,.1)', 'border-color' : '#c60f13'});
                }
            } else {
                _form.find("[name='mtm']").addClass('error_part').val('');
                _form.find("[name='serial_number']").addClass('error_part').val('');
                _form.find("[name='device_name']").addClass('error_part').val('');
                _form.find("[name='declaration_number']").addClass('error_part').val('');
            }
        }
    });
    return false;
});

// Отправляем форму если проходит валидацию
$('#add-dec-number-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-dec-number-form input').hasClass('is-invalid-input')
        || $('#add-dec-number-form input').hasClass('error_part')) { // проверка на валидность
        return false;
    } else {
        $('#add-dec-number-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 2000);
    }
});


// Изменяем SO
var id_psr = null;
$(document).on('click', '.edit-so', function(e) {
    e.preventDefault();
    $('#edit-so').foundation('open');
    var psr_so = $(this).siblings('.psr_so').text();
    $("#psr_so").val(psr_so.trim());
    id_psr = $(this).parent('td').parent('tr').data('id');
});
$(document).on('click', '#send-psr-so', function(e) {
    e.preventDefault();
    var psr_so = $("#psr_so").val();
    var data = "action=edit_so&id_psr=" + id_psr + "&psr_so=" + psr_so;

    $.ajax({
        url: "/adm/psr/psr_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            console.log(response);
            if(response == 200){
                $('[data-id="' + id_psr + '"]').find('.psr_so').text(psr_so).css('color', 'green');
                $('#edit-so form')[0].reset();
                $('#edit-so').foundation('close');
            } else {
                alert('Ошибка! Не удалось обновить запись!');
            }
        }
    });
});


// Редиктируем статус реквеста
$(document).on('click', '.edit-psr-status', function(e) {
    e.preventDefault();
    $('#edit-status').foundation('open');
    var psr_status = $(this).children('.psr_status').text();
    $('#psr_status option').each(function(){
        if($(this).val() == psr_status){
            $(this).removeAttr('selected').attr('selected', 'selected');
        }
    });
    $("#order_status").val(psr_status.trim());
    id_psr = $(this).parent('tr').data('id');
});
$(document).on('click', '#send-psr-status', function(e) {
    e.preventDefault();
    var psr_status = $("#psr_status").val();
    var data = "action=edit_status&id_psr=" + id_psr + "&psr_status=" + psr_status;

    $.ajax({
        url: "/adm/psr/psr_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 200){
                $('[data-id="' + id_psr + '"]').find('.psr_status').parent('td').removeClass('green orange yellow')
                    .addClass(obj.class);
                $('[data-id="' + id_psr + '"]').find('.psr_status').text(psr_status);
                $('#edit-status form')[0].reset();
                $('#edit-status').foundation('close');
            } else {
                alert('Ошибка! Не удалось обновить запись!');
            }
        }
    });
});
