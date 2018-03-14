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
    let user_address = $(this).parent('td').parent('tr').find('.user-address').text();
    let user_phone = $(this).parent('td').parent('tr').find('.user-phone').text();
    $("#user_address").val(user_address.trim());
    $("#user_phone").val(user_phone.trim());
    id_address = $(this).data('id');
});

$(document).on('click', '#send-user-address', function(e) {
    e.preventDefault();
    let address = $("#user_address").val();
    let phone = $("#user_phone").val();

    $.ajax({
        url: "/adm/user/address/update",
        type: "POST",
        data: {action : 'edit_address', id_address : id_address, address : address, phone : phone},
        cache: false,
        success: function (response) {
            if(response == 200){
                let row_id = $('[data-id="' + id_address + '"]');
                row_id.parent('td').parent('tr').find('.user-address').text(address).css('color', 'green');
                row_id.parent('td').parent('tr').find('.user-phone').text(phone).css('color', 'green');
                $('#edit-user-address').foundation('close');
                showNotification('Запись успешно обновленна','success');
            } else {
                showNotification('Ошибка! Не удалось обновить запись!','error');
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
            $('#control-manager').removeClass('hide');
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
            $('#control-manager').addClass('hide');
            $('#register-umbrella').addClass('medium-offset-3');
            $('#register-user').removeClass('medium-offset-3 medium-12').addClass('medium-6 medium-offset-3');
            $('select[name="curency_id"]').removeAttr('required').removeClass('required');
            $('select[name="to_electrolux"]').removeAttr('required').removeClass('required');
            $('select[name="to_mail_send"]').removeAttr('required').removeClass('required');
            //$('select[name="stock_place_id"]').removeAttr('required').removeClass('required');
            $('select[name="region_id"]').removeAttr('required').removeClass('required');
    }
});

// check gorup users
$(document).on('change', '.select-group', (event) => {
    const element = $(event.target);
    const selected = element.prop('checked');
    const inputs = element.parents('.parent-block').find('.children-input-group');
    for(let i = 0; i < inputs.length; i++) {
        $(inputs[i]).prop('checked', selected);
    }
});

// SlideToggle users in group
$(document).ready(function(){
    $('.parent-block .aqua').on('click', function() {
        $('.child-block  .show').slideToggle(500);
        $(this).parent().find('.show').slideToggle(500);
    });
});


// check all users
$('#checkAll').click(function () {
    $('input.delete_user:checkbox').prop('checked', this.checked);
});