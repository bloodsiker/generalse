//=============================================================
    //=======================CALENDAR=============================== 
    //=============================================================
    $(".date, #so-create-date").datepicker({
        buttonText: "Choose",
        regional: 'en-GB',
        dateFormat: 'yy-mm-dd'
    });


// Scroll top
$(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() != 0) {
            $('#toTop').fadeIn();
        } else {
            $('#toTop').fadeOut();
        }
    });

    $('#toTop').click(function () {
        $('body,html').animate({scrollTop: 0}, 300);
    });
});


$("#login").before("<span class='login_busy'></span>");
$(function() {
    $(document).on('keyup', '#login', function(e) {

        if ($("#login").val().length > 1) {
            var login = $("#login").val();

            $.ajax({
                type: "POST",
                url: "/adm/user/check_login",
                data: {login : login},
                cache: false,
                success: function(response) {
                    //alert(response);
                    console.log(response);
                    if (response == 2) { // error
                        $("#login").css('background-color','rgba(198, 15, 19, 0.1)');
                        $('.login_busy').text(login + ' занят').css('color', 'red');
                        $('#add_user').attr('disabled', 'true');
                    } else if (response == 1) { // ok
                        $("#login").css('background-color','rgba(198, 15, 19, 0)');
                        $('.login_busy').html('');
                        $('#add_user').removeAttr('disabled');
                    }
                }
            });
            return false;
        }
    });
});


    //=============================================================
    //=======================TABS=============================== 
    //=============================================================
    //$('#country').change(function(event) {
    //    if (!event.target.value == '') {
    //        $('#request-type').removeAttr('disabled');
    //        $('#request-type').change(function(event) {
    //            var requestTypeValue = event.target.value;
    //            switch (requestTypeValue) {
    //                case "Warranty Exception":
    //                    $('.warranty').show();
    //                    $('.warranty .required').attr('required', 'required');
    //                    $('.general').hide();
    //                    $('.general .required').removeAttr('required');
    //
    //                    break;
    //                case "General Service Query":
    //                    $('.warranty').hide();
    //                    $('.warranty .required').removeAttr('required');
    //                    $('.general').show();
    //                    $('.general .required').attr('required', 'required');
    //                    break;
    //                case "Refund":
    //                    $('.warranty').hide();
    //                    $('.warranty .required').removeAttr('required');
    //                    $('.general').show();
    //                    $('.general .required').attr('required', 'required');
    //                    break;
    //                default:
    //                    $('.warranty').hide();
    //                    $('.general').hide();
    //            }
    //        });
    //    }
    //});

    //=============================================================
    //=======================GENERAL and REFUND=============================== 
    //=============================================================

    // Multiple Request

hideInputM();
function hideInputM() {
    if ($('#multiple-request').is(':checked')) {
        $('.multiple-request-hide').hide();
        $('.missing-part .required').removeClass('required').removeAttr('required');
        $('.multiple-request-hide .required').removeAttr('required');
        $('#SN_check').removeAttr('pattern');
    } else {
        $('.multiple-request-hide').show();
        $('.missing-part').hide();

        $('.multiple-request-hide .required').attr('required', 'required');
        $('#SN_check').attr('pattern', '.{8,}');

    }
}

    $('#multiple-request').change(function(event) {
        hideInputM();
    });

    // Refund Reason

    $('#refund-reason').change(function(event) {
        switch(event.target.value) {
            case "Part_shortage":
                $('.missing-part').show();
                $('.missing-part input').addClass('required').attr('required', 'required');

                $('.doa-validation-results').hide();
                break;
            case "DOA":
                $('.doa-validation-results').show();
                $('.missing-part').hide();
                $('.missing-part .required').removeClass('required').removeAttr('required');
                break;
            default:
                $('.missing-part').hide();
                $('.missing-part input').removeClass('required').removeAttr('required');
                $('.doa-validation-results').hide();
        }
    });



// Search part number
$("#PN_check").before("<span class='pn_not_found'></span>");
$(function() {
    $(document).on('keyup', '#PN_check', function(e) {

        if ($("#PN_check").val().length > 1) {
            var pn_number = $("#PN_check").val();
            var data = "pn_number=" + pn_number;

            //alert(search);
            $.ajax({
                type: "POST",
                url: "/adm/part_num_ajax",
                data: data,
                cache: false,
                success: function(response) {
                    //alert(response);
                    if (response == 2) { // error
                        $("#PN_check").css('background-color','rgba(198, 15, 19, 0.1)');
                        $('.pn_not_found').text('Part number is not found');
                    } else if (response == 1) { // ok
                        $("#PN_check").css('background-color','rgba(198, 15, 19, 0)');
                        $('.pn_not_found').html('');
                    }
                },
                error: function(){
                    $("#PN_check").css('background-color','rgba(198, 15, 19, 0.1)');
                    $('.pn_not_found').text('Part number is not found');
                }
            });
            return false;
        }
    });
});



// Load lis attachment file
$(document).on('click', '.file_request', function(){

    $('#container-file').html("");
    var id_request = $(this).data('file');
    var data = "action=file&id_request=" + id_request;

    $.ajax({
        type: "POST",
        url: "/adm/request_ajax",
        data: data,
        cache: false,
        success: function(response) {
            $('#container-file').html(response);
        }
    });
    return false;
});


// Показываем в модальном окне, комментарии в заявках на списание Refund Request
$(document).on('dblclick', '#request .comment', function(e) {
    var comment = $(this).attr('data-comment');

    if(comment.length != 0){
        $('#show-comment').foundation('open');
        $('.modal_comment').html(comment);
    }
});

// $(".comment").on({
//     mouseenter: function(){
//
//         //stuff to do on mouse enter
//         var _this = $(this);
//         _this.children('.add-lenovo-num').removeClass('hide');
//
//     },
//     mouseleave: function () {
//         //stuff to do on mouse leave
//         var __this = $(this);
//         __this.children('.add-lenovo-num').addClass('hide');
//
//     }});


// Открываем модальное окно, и вносим комментарий
var id_warranty = null;
$(document).on('click', '.add-lenovo-num', function(e) {
    e.preventDefault();
    $('#add-lenovo-num').foundation('open');
    var lenovo_num_val = $(this).siblings('.text-lenovo-num').text();
    $("#lenovo_num").val(lenovo_num_val);
    id_warranty = $(this).parent('td').parent('tr').data('id');

});
// Вносим изменения в модальном окне
$(document).on('click', '#send-lenovo-num', function(e) {
    e.preventDefault();
    var lenovo_num = $("#lenovo_num").val();
    var data = "action=add_lenovo_num&id_warranty=" + id_warranty + "&lenovo_num=" + lenovo_num;

    $.ajax({
        url: "/adm/request_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function () {
            $('[data-id="' + id_warranty + '"]').find('.text-lenovo-num').text(lenovo_num);
            $('#add-lenovo-num form')[0].reset();
            $('#add-lenovo-num').foundation('close');
        }
    });
});



// Проверка SN на минимум 8 символов
$(document).on('keyup', '#SN_check', function(e) {
    if (e.target.value.length < 8) {
        $('#spanError').text('at least 8 characters');
    } else {
        $('#spanError').text('');
    }
});


// Проверка валидации формы
$('#form_warranty').on("submit", function(e) {
    if ($('.pn_not_found').html().length > 0) {
        return e.preventDefault();
    }
});

$('#kpi').on("submit", function(e) {
    if ($('.date-start').html().length > 0) {
        return e.preventDefault();
    }
});



// По клику на кнопку, подгрузка по 50 логов
$(document).ready(function () {
    $("#imgLoad").hide();  //Скрываем прелоадер
    var num = 30; //чтобы знать с какой записи вытаскивать данные
    $(function () {
        $("#load-log").click(function (e) { //Выполняем если по кнопке кликнули
            e.preventDefault();
            $("#imgLoad").show(); //Показываем прелоадер
            var data = "num=" + num + "&action=load_log";
            $.ajax({
                url: "/adm/user/ajax_logs",
                type: "POST",
                data: data,
                cache: false,
                success: function (response) {
                    if (response == 0) {  // смотрим ответ от сервера и выполняем соответствующее действие
                        //alert("Больше нет записей");
                        $('#load-log').remove();
                        $('.button_load').html("<div class='button primary' style='width: inherit;'>Больше нет записей</div>");
                        $("#imgLoad").hide();
                    } else {
                        $("tbody").append(response);
                        num = num + 30;
                        $("#imgLoad").hide();
                    }
                }
            });
        });
    });
});

// По клику на кнопку, подгрузка по 30 заявок
$(document).ready(function () {
    $("#imgLoad").hide();  //Скрываем прелоадер
    var num_req = 30; //чтобы знать с какой записи вытаскивать данные
    $(function () {
        $("#load-request").click(function (e) { //Выполняем если по кнопке кликнули
            e.preventDefault();
            $("#imgLoad").show(); //Показываем прелоадер
            var data = "num_req=" + num_req + "&action=load_refund_request";
            $.ajax({
                url: "/adm/request_ajax",
                type: "POST",
                data: data,
                cache: false,
                success: function (response) {
                    if (response == 0) {  // смотрим ответ от сервера и выполняем соответствующее действие
                        //alert("Больше нет записей");
                        $('#load-log').remove();
                        $('.button_load').html("<div class='button primary' style='width: inherit;'>Больше нет записей</div>");
                        $("#imgLoad").hide();
                    } else {
                        $("tbody").append(response);
                        num_req = num_req + 30;
                        $("#imgLoad").hide();
                        var table = document.getElementById('table_refund');//получаем элемент таблицы
                        var count = table.rows.length;//количество строк
                        $('#count_refund').html("(" + (count - 1) + ")");
                    }
                }
            });
        });
    });
});


// Отмечаем строки в таблице, которые отправили в Lenovo
$(document).on('click', '.check_lenovo', function(){

    var id_warranty = $(this).parent('tr').data('id');
    var self = $(this);
    //alert(id_warranty);
    var data = "action=check_lenovo&id_warranty=" + id_warranty;

    $.ajax({
        type: "POST",
        url: "/adm/request_ajax",
        data: data,
        cache: false,
        success: function(response) {
            if(response == 1){
                self.parent('tr').addClass('check_lenovo_ok');
                self.removeClass('check_lenovo').addClass('uncheck_lenovo');
            }
        }
    });
    return false;
});

// Убираем отметки строк в таблице, которые отправили в Lenovo
$(document).on('click', '.uncheck_lenovo', function(){

    var id_warranty = $(this).parent('tr').data('id');
    var self = $(this);
    //alert(id_warranty);
    var data = "action=uncheck_lenovo&id_warranty=" + id_warranty;

    $.ajax({
        type: "POST",
        url: "/adm/request_ajax",
        data: data,
        cache: false,
        success: function(response) {
            if(response == 1){
                self.parent('tr').removeClass('check_lenovo_ok');
                self.removeClass('uncheck_lenovo').addClass('check_lenovo');
            }
        }
    });
    return false;
});

// Подтверждение заказов
$(document).on('click', '.refund-accept', function(e) {
    e.preventDefault();
    var self = $(this);
    var refund_id = self.parents('td').parents('tr').attr('data-gm-id');
    $('#table_refund').find('tr').removeClass("blue");
//alert(refund_id);
    $.ajax({
        url: "/adm/request_ajax",
        type: "POST",
        data: {refund_id : refund_id, action : 'accept'},
        cache: false,
        success: function (response) {
            console.log((response));
            var obj = JSON.parse(response);
            if(obj.ok == 1){
                self.parents('td').parents('tr').addClass("blue");
                self.parents('td').parents('tr').find('td').eq(14).removeClass().addClass(obj.class).text(obj.text);
                self.parents('td').find('a').remove();
            } else {
                alert(obj.error);
            }
        }
    });
    return false;
});

// Отказ заказа
$(document).on('click', '.refund-dismiss', function(e) {
    e.preventDefault();
    $('.dismiss-container').remove();
    var self = $(this);
    var refund_id = self.parents('td').parents('tr').attr('data-gm-id');
    $('#table_refund').find('tr').removeClass("blue");
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
            url: "/adm/request_ajax",
            type: "POST",
            data: {refund_id : refund_id, action : 'dismiss', comment : comment},
            cache: false,
            success: function (response) {
                console.log((response));
                var obj = JSON.parse(response);
                if(obj.ok == 1){
                    self.parents('td').parents('tr').find('td').eq(14).removeClass().addClass(obj.class).text(obj.text);
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



/******************  BATCH  ****************************/

// Коггда форма отправленная, делаем кнопку отправки не активной
$('#add-batch-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-batch-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#wait').removeClass('hide');
        $(this).prop('disabled', true);
        setTimeout(function () {
            e.target.submit();
        }, 3000);
    }
});

/******************  BackLog  ****************************/

// Коггда форма отправленная, делаем кнопку отправки не активной
$('#add-backlog-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-backlog-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#wait').removeClass('hide');
        $(this).prop('disabled', true);
        setTimeout(function () {
            e.target.submit();
        }, 3000);
    }
});