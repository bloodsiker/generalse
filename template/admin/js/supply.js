// ================ SEND FORM ======================
$('#add-supply-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-supply-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#add-supply-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 3000);
    }
});

// ================ SEND FORM ADD PARTS IN SUPPLY ======================
$('#add-parts-supply-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-parts-supply-form input').hasClass('is-invalid-input') || $('#add-parts-supply-form input').hasClass('error_site_id')) { // проверка на валидность
        return false;
    } else {
        $('#add-parts-supply-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 3000);
    }
});



//ID SUPPLY SEARCH
$('[name="site_id"]').keyup(function(e) {
    //ajax e.target.value
    var site_id = e.target.value;
    var action = 'search_site_id';
    $.ajax({
        url: "/adm/crm/supply_action_ajax",
        type: "POST",
        data: {site_id : site_id, action : action},
        cache: false,
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);

            if (obj.status == 404) {
                $("[name='site_id']").addClass('error_site_id');
                $('.supply_site_id').text(obj.name).css('color', '#ef4242');
            } else if (obj.status == 403) {
                var html = "<span class='site_id_warning' style='color: #ef4242'>" + obj.warning + "</span>"
                $('.supply_site_id').text(obj.name).css('color', '#4CAF50');
                $('.supply_site_id').append(html);
                $("[name='site_id']").addClass('error_site_id');
            } else {
                $('.supply_site_id').text(obj.name).css('color', '#4CAF50');
                $("[name='site_id']").removeClass('error_site_id', 'site_id_warning');
            }
        }
    });
    return false;
});



// ================ MODEL ======================
// var model_data = {};
// var count_checked = 0;
// // =============== CHECKBOX ===================
// checked_filed = function (
//     event,
//     number
// ) {
//
//     if (event.target.checked) {
//         ++count_checked;
//         model_data[number] = {
//             site_id: number
//         };
//     } else {
//         --count_checked;
//         delete model_data[number];
//     }
//     return count_checked > 0 ? $('#send_button').removeAttr('disabled') : $('#send_button').attr('disabled','');
// };



// =================== SEND ===============

send = function (event, site_id) {
    event.preventDefault();
    var _this = $(event.target);

    $('#wait').removeClass('hide');

    $.ajax({
        url: "/adm/crm/supply_ajax",
        type: "POST",
        data: {site_id : site_id},
        cache: false,
        success: function (response) {
            setTimeout(function () {
                $('#wait').addClass('hide');
                if(response == 200){
                    _this.parent('td').append("<span>Success</span>");
                    _this.remove();
                } else {
                    alert('Ошибка! Не удалось разгрузить на склад!');
                }
            }, 2000);
        }
    });
    return false;
};


// Показываем в модальном окне, продукты покупки
$(document).on('dblclick', '.checkout tbody tr', function(e) {
    var site_id = $(this).attr('data-siteid');
    //alert(site_id);
    $.ajax({
        url: "/adm/crm/show_supply",
        type: "POST",
        data: {site_id : site_id},
        cache: false,
        success: function (response) {
            //alert(response);
            $('#show-details').foundation('open');
            $('#container-details').html(response);

            $.ajax({
                url: "/adm/crm/supply_action_ajax",
                type: "POST",
                data: {site_id : site_id, action : 'quantity'},
                cache: false,
                success: function (resp) {
                    var obj = JSON.parse(resp);
                    $('.supply_count').text(obj.supply).css('color', 'green');
                    $('.supply_reserve_count').text(obj.reserve).css('color', 'red');
                }
            });
        }
    });
    return false;
});


// Привязываем поставку к GM
$(document).on('click', '.supply-bind-gm', function(e) {
    e.preventDefault();

    var site_id = $(this).parent('td').parent('tr').data('siteid');
    var action = 'bind_gm';

    console.log(site_id);
    $.ajax({
        url: "/adm/crm/supply_action_ajax",
        type: "POST",
        data: {site_id : site_id, action : action},
        cache: false,
        success: function (response) {
            console.log(response);
            if(response == 200){

                $('[data-siteid="' + site_id + '"]').find('.td-bind-gm').text('success').css('color', 'green');
                //$('[data-siteid="' + site_id + '"]').find('.status-supply').text('Подтверждена').addClass('green');

            } else {
                alert('Ошибка! Не удалось привязать к GM!');
            }
        }
    });
    return false;
});


// Удаляем поставку, только те, что не привязаны к GM
$(document).on('click', '.supply-delete', function(e) {
    e.preventDefault();

    var site_id = $(this).parent('td').parent('tr').data('siteid');
    var action = 'delete_supply';

    console.log(site_id);
    $.ajax({
        url: "/adm/crm/supply_action_ajax",
        type: "POST",
        data: {site_id : site_id, action : action},
        cache: false,
        success: function (response) {
            console.log(response);
            if(response == 200){

                var tr = $('[data-siteid="' + site_id + '"]');
                tr.css('background', '#ccc');
                tr.find('.td-supply-delete').text('deleted!').css('color', 'red');
                tr.find('.td-bind-gm').text('');
            } else if(response == 403) {
                alert('Ошибка! Не возможно удалить поставку привязаную к GM');
            } else {
                alert('Ошибка! Не удалось удалить поставку!');
            }
        }
    });
    return false;
});