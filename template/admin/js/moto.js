$(document).on('click', '#create-rep-button', function(e) {
	$('#create-rep form')[0].reset();
	$('#create-rep').foundation('open');
    $('.name-product').text('');
});

$(document).on('click', '#create-parts-button', function(e) {
    $('#create-parts form')[0].reset();
    $('#create-parts').foundation('open');
    $('.name-product').text('');
});

$(document).on('click', '#create-locsource-button', function(e) {
    $('#create-locsource form')[0].reset();
    $('#create-locsource').foundation('open');
    $('.name-product').text('');
});

$(document).on('click', '#create-closerepeir-button', function(e) {
    $('#create-closerepeir form')[0].reset();
    $('#create-closerepeir').foundation('open');
    $('.name-product').text('');
});




//PART NUMBER SEARCH
$('[name="mtm"]').keyup(function(e) {
    //ajax e.target.value
    $('[type="submit"]').removeAttr('disabled');
    var mtm = e.target.value;
    $.ajax({
        url: "/adm/crm/moto_part_num_ajax",
        type: "POST",
        data: {mtm : mtm},
        cache: false,
        success: function (response) {
            //var obj = JSON.parse(response);

            if(response == 0){
                $("[name='goods_name']").val('');
               $("[name='mtm']").addClass('error_part');
               $('.name-product').text('not found').css('color', 'red');
            } else {
               //console.log(obj[0].mName);
               $("[name='goods_name']").val(response);
               $('.name-product').text(response).css('color', '#4CAF50');
               $("[name='mtm']").removeClass('error_part');
            }
        }
    });

    return false;
});


// $(function() {
//     $('#create-new-repair [type="file"]').on('change', function(e) {
//         var files = e.target.files;
//         for (var i = 0; i <= files.length - 1; i++) {
//            var file = e.target.files[i];
//             console.log(file.size);
//         }
//     });
// });


// отправить запрос
$('#create-new-repair').submit(function(e) {
    e.preventDefault();
    if ($('#create-new-repair input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } if($('.name-product').text() == 'not found') {
        return false;
    } else {
        $('#create-new-repair').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 3000);
    }
});


// Проверка на наличия серийного номера Add Parts
$('[name="serial_num_parts"]').keyup(function(e) {
    //ajax e.target.value
    var serial_number = e.target.value;
    $('.serial_num').text(serial_number);
    console.log(serial_number);
    $.ajax({
        url: "/adm/crm/moto_serial_num_ajax",
        type: "POST",
        data: {serial_number : serial_number},
        cache: false,
        success: function (response) {
            var obj = JSON.parse(response);

            if(obj == 0){
                $("[name='site_id']").val('');
                $("[name='serial_num_parts']").addClass('error_part');
                $('.serial_num').text(serial_number).css('color', 'red');
            } else {
                //console.log(obj[0].mName);
                $("[name='site_id']").val(obj.site_id);
                $('.serial_num').text(obj.serial_number).css('color', '#4CAF50');
                $("[name='serial_num_parts']").removeClass('error_part');
            }
        }
    });

    return false;
});

// отправить запрос Add Parts
$('#add-parts').submit(function(e) {
    e.preventDefault();
    if ($('#add-parts input').hasClass('is-invalid-input') || $('#add-parts input').hasClass('error_part')) { // проверка на валидность
        return false;
    } if($('.name-product').text() == 'not found') {
        return false;
    } else {
        $('#add-parts').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 3000);
    }
});




// Проверка на наличия серийного номера Add Local Source
$('[name="serial_num_local"]').keyup(function(e) {
    //ajax e.target.value
    var serial_number = e.target.value;
    $('.serial_num_local').text(serial_number);
    $.ajax({
        url: "/adm/crm/moto_serial_num_ajax",
        type: "POST",
        data: {serial_number : serial_number},
        cache: false,
        success: function (response) {
            var obj = JSON.parse(response);

            if(obj == 0){
                $("[name='site_id']").val('');
                $("[name='serial_num_local']").addClass('error_part');
                $('.serial_num_local').text(serial_number).css('color', 'red');
            } else {
                //console.log(obj[0].mName);
                $("[name='site_id']").val(obj.site_id);
                $('.serial_num_local').text(obj.serial_number).css('color', '#4CAF50');
                $("[name='serial_num_local']").removeClass('error_part');
            }
        }
    });

    return false;
});

// отправить запрос Add Local Source
$('#add-local-source').submit(function(e) {
    e.preventDefault();
    if ($('#add-local-source input').hasClass('is-invalid-input') || $('#add-local-source input').hasClass('error_part')) { // проверка на валидность
        return false;
    } if($('.name-product').text() == 'not found') {
        return false;
    } else {
        $('#add-local-source').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 3000);
    }
});


// Проверка на наличия серийного номера Close Repair
$('[name="serial_num_close"]').keyup(function(e) {
    //ajax e.target.value
    var serial_number = e.target.value;
    $('.serial_num_close').text(serial_number);
    console.log(serial_number);
    $.ajax({
        url: "/adm/crm/moto_serial_num_ajax",
        type: "POST",
        data: {serial_number : serial_number},
        cache: false,
        success: function (response) {
            var obj = JSON.parse(response);

            if(obj == 0){
                $("[name='site_id']").val('');
                $("[name='serial_num_close']").addClass('error_part');
                $('.serial_num_close').text(serial_number).css('color', 'red');
            } else {
                //console.log(obj[0].mName);
                $("[name='site_id']").val(obj.site_id);
                $('.serial_num_close').text(obj.serial_number).css('color', '#4CAF50');
                $("[name='serial_num_close']").removeClass('error_part');
            }
        }
    });

    return false;
});

// отправить запрос Close Repair
$('#close-repair').submit(function(e) {
    e.preventDefault();
    if ($('#close-repair input').hasClass('is-invalid-input') || $('#close-repair input').hasClass('error_part')) { // проверка на валидность
        return false;
    } else {
        $('#close-repair').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
                e.target.submit()
        }, 3000);
    }
});


// Показываем в модальном окне, дополнительную информацию
$(document).on('dblclick', '.checkout tbody tr', function(e) {
    var site_id = $(this).attr('data-siteid');

    $.ajax({
        url: "/adm/crm/show_moto",
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