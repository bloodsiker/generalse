//При загрузке дашборда после авторизации, показываем прелоудер
$(document).ready(function () {
    setTimeout(function () {
        $('#general-wait').addClass('hide');
    }, 4000);
});

$(document).on('click', '#expand-balance', function () {
    $('.action_output_balance').slideToggle('fast');
});

// После клика на кнопку выплатить, делаем не активной
$('#send_output_balance').click(function (e) {
    $('.dashboard-form').submit();
    $('#wait').removeClass('hide');
    $(this).prop('disabled', true);
    setTimeout(function () {

    }, 3000);
});


// $(document).on('keyup', '#output_balance', function (e) {
//     var max = $("[name='receive_funds']").attr('max');
//     var output_balance = $('#output_balance').val();
//     console.log(max);
//     if (e.target.value > max) {
//         e.target.value = max;
//     }
//     if (e.target.value <= 0) {
//         e.target.value = 0;
//         $('#send_output_balance').prop('disabled', true);
//     }
// });

function checkBalance() {
    $(document).ready(function () {
        var max = $("[name='receive_funds']").attr('max');
        var output_balance = $('#output_balance').val();
        console.log(output_balance);
        if(output_balance > max){
            output_balance = max;
        }
        if (output_balance <= 0) {
            output_balance = 0;
            $('#send_output_balance').prop('disabled', true);
        }
    });
}
checkBalance();


// Подтверждение выплаты
$(document).on('click', '.ok-paid', function(e) {
    e.preventDefault();
    var self = $(this);
    var paid_id = self.parents('td').parents('tr').attr('data-paid-id');
    $('.dashboard-section').find('tr').removeClass("blue");
    console.log(paid_id);

    $.ajax({
        url: "/adm/dashboard/ajax_balance",
        type: "POST",
        data: {paid_id : paid_id, action : 'accept'},
        cache: false,
        success: function (response) {
            console.log((response));
            var obj = JSON.parse(response);
            if(obj.ok == 1){
                self.parents('td').parents('tr').addClass("blue");
                self.parents('td').parents('tr').find('td').eq(6).removeClass().addClass(obj.class).text(obj.text);
                self.parents('td').parents('tr').find('.paid').remove();
            } else {
                alert(obj.error);
            }
        }
    });
    return false;
});


// Отказ выплаты
$(document).on('click', '.no-paid', function(e) {
    e.preventDefault();
    $('.dismiss-container').remove();
    var self = $(this);
    var paid_id = self.parents('td').parents('tr').attr('data-paid-id');
    $('.dashboard-section').find('tr').removeClass("blue");
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
            url: "/adm/dashboard/ajax_balance",
            type: "POST",
            data: {paid_id : paid_id, action : 'dismiss', comment : comment},
            cache: false,
            success: function (response) {
                console.log((response));
                var obj = JSON.parse(response);
                if(obj.ok == 1){
                    self.parents('td').parents('tr').find('td').eq(6).removeClass().addClass(obj.class).text(obj.text);
                    self.parents('td').parents('tr').find('.paid').remove();
                    $('.dismiss-container').remove();
                } else {
                    alert(obj.error);
                }
            }
        });
        return false;
    });
});


// Открываем подробную информацию об операции
$(document).on('dblclick', '.info-operation', function (e) {
    e.preventDefault();
    var section = $(this).attr('data-section');
    var id_row = $(this).attr('data-row-id');
    var id_task = $(this).attr('data-task-id');
    var action = 'show';

    $.ajax({
        url: "/adm/dashboard/ajax_show_info",
        type: "POST",
        data: {section : section, id_row : id_row, id_task : id_task, action: action},
        cache: false,
        success: function (response) {
            //console.log(response);
            $('#show-info').foundation('open');
            $('#container-details').html(response);
        }
    });
    return false;
});


// Отображаем форму для начисление штрафных санкций
$('#penalty').click(function (e) {
    e.preventDefault();
    $('#block-penalty').removeClass('hide');
});

// Скрываем форму для начисление штрафных санкций
$('#cancel-penalty').click(function (e) {
    e.preventDefault();
    $('#block-penalty').addClass('hide');
});

// Насчитываем штрафные санкции, делаем кнопку не активной
$('#send-penalty-form').submit(function(e) {
    e.preventDefault();
    $('#wait').removeClass('hide');
    $(this).prop('disabled', true);
    setTimeout(function () {
        e.target.submit();
    }, 3000);
});
