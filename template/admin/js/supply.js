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

// ================ MODEL ======================
var model_data = {};
var count_checked = 0;
// =============== CHECKBOX ===================
checked_filed = function (
    event,
    number
) {

    if (event.target.checked) {
        ++count_checked;
        model_data[number] = {
            site_id: number
        };
    } else {
        --count_checked;
        delete model_data[number];
    }
    return count_checked > 0 ? $('#send_button').removeAttr('disabled') : $('#send_button').attr('disabled','');
};

// ==================== DBCLICK =============
// show_field = function (
//     part_number,
//     description_pn,
//     so_number
// ) {
//
//     $('#refactor-field-modal').foundation('open');
//     $('#part-number').val(part_number);
//     $('#description-pn').val(description_pn);
//     $('#so-number').val(so_number);
// };


// =================== SEND ===============

send = function () {
    //console.log(model_data);
    var json = JSON.stringify(model_data); // json
    console.log(json); // send to server json AJAX
    $('#send_button').prop('disabled', true);
    document.body.style.cursor = 'wait';
    $.ajax({
        url: "/adm/crm/supply_ajax",
        type: "POST",
        data: {json : json},
        cache: false,
        success: function (response) {
            console.log(response);
            window.location.reload();
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
        }
    });
    return false;
});