$(document).on('click', '#add-psr', function(e) {
    $('#add-psr-modal form')[0].reset();
    $('.name-product').text('');
    $('.error-date').hide();
    $('#add-psr-modal').foundation('open');
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



// Изменяем SO
var id_psr = null;

// Редактируем номер декларации
$(document).on('click', '.edit-dec', function(e) {
    e.preventDefault();
    $('#edit-dec').foundation('open');
    var psr_dec = $(this).siblings('.psr-dec-number').text();
    $("#psr_dec").val(psr_dec.trim());
    id_psr = $(this).parents('[data-id]').attr('data-id');
});
$(document).on('click', '#send-psr-dec', function(e) {
    e.preventDefault();
    var psr_dec = $("#psr_dec").val();
    var data = "action=edit_dec&id_psr=" + id_psr + "&psr_dec=" + psr_dec;

    $.ajax({
        url: "/adm/psr/psr_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            console.log(response);
            if(response == 200){
                $('[data-id="' + id_psr + '"]').find('.psr-dec-number').text(psr_dec).css('color', 'green');
                $('#edit-dec form')[0].reset();
                $('#edit-dec').foundation('close');
            } else {
                alert('Ошибка! Не удалось обновить запись!');
            }
        }
    });
});


// Редактируем номер декларации для возврата
$(document).on('click', '.edit-dec-return', function(e) {
    e.preventDefault();
    $('#edit-dec-return').foundation('open');
    var psr_dec = $(this).siblings('.psr-dec-number-return').text();
    $("#psr_dec_return").val(psr_dec.trim());
    id_psr = $(this).parents('[data-id]').attr('data-id');
});
$(document).on('click', '#send-psr-dec-return', function(e) {
    e.preventDefault();
    var psr_dec = $("#psr_dec_return").val();
    var data = "action=edit_dec_return&id_psr=" + id_psr + "&psr_dec=" + psr_dec;

    $.ajax({
        url: "/adm/psr/psr_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            console.log(response);
            if(response == 200){
                $('[data-id="' + id_psr + '"]').find('.psr-dec-number-return').text(psr_dec).css('color', 'green');
                $('#edit-dec-return form')[0].reset();
                $('#edit-dec-return').foundation('close');
            } else {
                alert('Ошибка! Не удалось обновить запись!');
            }
        }
    });
});