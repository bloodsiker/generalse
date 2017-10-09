// Switch input pyte
function switch_type() {
    if($('#user-password').attr('type') == 'password'){
        $('#user-password').attr('type', 'text');
    } else {
        $('#user-password').attr('type', 'password');
    }
    return true;
}

// Generate random password
function password_rand() {
    var login        = $('#login').val();
    var result       = '';
    //var words        = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM#@!-_$%';
    var words        = '0123456789#@!-_$%';
    var max_position = words.length - 1;
    for( i = 0; i < 4; ++i ) {
        position = Math.floor ( Math.random() * max_position );
        result = result + words.substring(position, position + 1);
    }
    var rand = 1 + Math.random() * (999 - 1);
    rand = Math.round(rand);
    $('#user-password').val(rand + login + result);
    return true;
}

// Показываем в модальном окне, дополнительную информацию
$(document).on('click', '.list-user-func', function(e) {
    var user_id = $(this).parent('td').parent('tr').attr('data-userid');

    $.ajax({
        url: "/adm/user/show_list_func",
        type: "POST",
        data: {user_id : user_id},
        cache: false,
        success: function (response) {
            //alert(response);
            $('#show-list-user-func').foundation('open');
            $('#container-details').html(response);
        }
    });
    return false;
});


// Показываем информацию о пользователе из GM
$(document).on('click', '.info-gm-user', function(e) {
    var user_id = $(this).parent('td').parent('tr').attr('data-userid');

    $.ajax({
        url: "/adm/user/info_gm_user",
        type: "POST",
        data: {user_id : user_id},
        cache: false,
        success: function (response) {
            //alert(response);
            console.log(response);
            $('#info-gm-user').foundation('open');
            $('#container-user-details').html(response);
        }
    });
    return false;
});


// Редактируем адрес поставки
var id_address = '';
$(document).on('click', '.edit-address', function(e) {
    e.preventDefault();
    $('#edit-user-address').foundation('open');
    var user_address = $(this).parent('td').parent('tr').find('.user-address').text();
    $("#user_address").val(user_address.trim());
    id_address = $(this).data('id');
});

$(document).on('click', '#send-user-address', function(e) {
    e.preventDefault();
    var address = $("#user_address").val();
    var data = "action=edit_address&id_address=" + id_address + "&address=" + address;

    $.ajax({
        url: "/adm/user/address/update",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            if(response == 200){
                $('[data-id="' + id_address + '"]').parent('td').parent('tr').find('.user-address').text(address).css('color', 'green');
                $('#edit-user-address form')[0].reset();
                $('#edit-user-address').foundation('close');
            } else {
                alert('Ошибка! Не удалось обновить запись!');
            }
        }
    });
});


// При успешной валидации отправляем заявку и показываем прелоудер
$('#create-user').submit(function(e) {
    e.preventDefault();
    if ($('#create-user input').hasClass('is-invalid-input') || $('#create-user select').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#create-user').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 2000);
    }
});


//При выборе роли пользователя, скрываем\показываем нужные поля для заполнения
$('select[name="role"]').change(function(event) {
    switch(event.target.value) {
        case "2":
            $('#register-gm').removeClass('hide');
            $('#register-umbrella').removeClass('medium-offset-3');
            $('#register-user').removeClass('medium-offset-3 medium-6').addClass('medium-12');
            $('select[name="curency_id"]').attr('required', 'required').addClass('required');
            $('select[name="to_electrolux"]').attr('required', 'required').addClass('required');
            $('select[name="to_mail_send"]').attr('required', 'required').addClass('required');
            //$('select[name="stock_place_id"]').attr('required', 'required').addClass('required');
            $('select[name="region_id"]').attr('required', 'required').addClass('required');
            break;
        default:
            $('#register-gm').addClass('hide');
            $('#register-umbrella').addClass('medium-offset-3');
            $('#register-user').removeClass('medium-offset-3 medium-12').addClass('medium-6 medium-offset-3');
            $('select[name="curency_id"]').removeAttr('required').removeClass('required');
            $('select[name="to_electrolux"]').removeAttr('required').removeClass('required');
            $('select[name="to_mail_send"]').removeAttr('required').removeClass('required');
            //$('select[name="stock_place_id"]').removeAttr('required').removeClass('required');
            $('select[name="region_id"]').removeAttr('required').removeClass('required');
    }
});