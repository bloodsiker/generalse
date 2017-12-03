
$('#add-request-import-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-request-import-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        $('#add-request-import-form').find('button').prop('disabled', true);
        $('#wait').removeClass('hide');
        setTimeout(function () {
            e.target.submit()
        }, 3000);
    }
});


// Открываем модальное окно, и редактируем price
var id_request = null;
$(document).on('click', '.edit-price', function(e) {
    e.preventDefault();
    $('#edit-price').foundation('open');
    var request_price = $(this).siblings('.request_price').text();
    $("#request_price").val(request_price.trim());
    id_request = $(this).parent('td').parent('tr').data('id');
});
// Вносим изменения в модальном окне
$(document).on('click', '#send-request-price', function(e) {
    e.preventDefault();
    var request_price = $("#request_price").val();
    var data = "action=edit_price&id_request=" + id_request + "&request_price=" + request_price;

    $.ajax({
        url: "/adm/crm/other-request/request_ajax",
        type: "POST",
        data: data,
        cache: false,
        success: function (response) {
            console.log(response);
            if(response == 200){
                $('[data-id="' + id_request + '"]').find('.request_price').text(request_price).css('color', 'green');
                $('#edit-price form')[0].reset();
                $('#edit-price').foundation('close');
                showNotification('Цена успешно установлена!', 'success');
            } else {
                showNotification('Не удалось обновить запись!', 'Ошибка! ', 'error');
            }
        }
    });
});


// Мягкое удаление
$(document).on('click', '.request-delete', function(e) {
    e.preventDefault();
    if (confirm("Вы уверены что хотите удалить?")) {

        id_request = $(this).parent('td').parent('tr').data('id');
        var data = "action=request_delete&id_request=" + id_request;

        $.ajax({
            url: "/adm/crm/other-request/request_ajax",
            type: "POST",
            data: data,
            cache: false,
            success: function (response) {
                if(response == 200){
                    var html = '<td>' + id_request + '</td>'
                        + '<td colspan="13" class="text-center">Request deleted</td>';
                    $('[data-id="' + id_request + '"]').html(html).css('background', '#9ef19d');
                    showNotification('Запись удалена!', 'success');
                } else {
                    showNotification('Не удалось удалить запись!', 'Ошибка! ', 'error');
                }
            }
        });
    } else {
        return false;
    }
});


// Согласование цен
$(document).on('click', '.request-action', function(e) {
    e.preventDefault();

    id_request = $(this).parent('td').parent('tr').data('id');
    var action = $(this).data('action');
    var data = "action=action&id_request=" + id_request + '&user_action=' + action;

    if (action == 4 || action == 2) {

        // Добавляем блок с комментариями
        if (action == 4) {

            var html = "<div class='dismiss-container'>" +
                "<textarea class='dismiss-comment' cols='30' placeholder='Комментарий' rows='3'></textarea>" +
                "<button id='send-dismiss'>Disagree</button>" +
                "<button id='send-close'>Close</button>" +
                "</div>";
            $(this).after(html);

        } else if (action == 2) {

            var html = "<div class='dismiss-container'>" +
                "<textarea class='dismiss-comment' cols='30' placeholder='Комментарий' rows='3'></textarea>" +
                "<button id='send-dismiss'>Отказать</button>" +
                "<button id='send-close'>Закрить</button>" +
                "</div>";
            $(this).after(html);
        }

        //Отправляем
        $('#send-dismiss').click(function () {
            var comment = $('.dismiss-comment').val();
            data += '&comment=' + comment;
            console.log(data);
            $.ajax({
                url: "/adm/crm/other-request/request_ajax",
                type: "POST",
                data: data,
                cache: false,
                success: function (response) {
                    console.log(response);
                    if(response == 200){
                        var element = $('[data-id="' + id_request + '"]');

                        if (action == 4) {
                            element.find('.action-control').text('Нет согласия').css('color', 'red');
                            element.find('.status').text('Нет согласия').removeClass('aqua').addClass('red');
                            showNotification('Заявки перешел в статус "Нет согласия"', 'success');
                        } else if (action == 2) {
                            element.find('.action-control').text('Отказано').css('color', 'red');
                            element.find('.status').text('Отказано').removeClass('yellow').addClass('red');
                            element.find('.edit-price').remove();
                            showNotification('Зявки перешел в статус "Отказано"', 'success');
                        }
                    } else {
                        showNotification('Не удалось отклонить!', 'Ошибка!', 'error');
                    }
                }
            });
            return false;
        });

        // Удаляем блок с комментариями
        $('#send-close').click(function () {
            $('.dismiss-container').remove();
        });

    } else {

        $.ajax({
            url: "/adm/crm/other-request/request_ajax",
            type: "POST",
            data: data,
            cache: false,
            success: function (response) {
                if(response == 200){
                    var element = $('[data-id="' + id_request + '"]');

                    if (action == 1) {
                        element.find('.action-control').text('Ожидаем действия партнера').css('color', 'green');
                        element.find('.status').text('Согласование').removeClass('yellow').addClass('aqua');
                        element.find('.edit-price').remove();
                        showNotification('Отправленно на согласование', 'success');
                    } else if(action == 2) {
                        element.find('.action-control').text('Отказано').css('color', 'red');
                        element.find('.status').text('Отказано').removeClass('yellow').addClass('red');
                        element.find('.edit-price').remove();
                        showNotification('Заявки перешел в статус "Отказано"', 'success');
                    } else if(action == 3) {
                        element.find('.action-control').text('Ожидается отправка').css('color', 'orange');
                        element.find('.status').text('Отправка').removeClass('aqua').addClass('orange');
                    } else if(action == 5) {
                        element.find('.action-control').text('Выполненный запрос').css('color', 'orange');
                        element.find('.status').text('Выполненный').removeClass('orange').addClass('green');
                        showNotification('Запрос успешно выполнен!', 'success');
                    }

                } else {
                    if (action == 1) {
                        showNotification('Не удалось отправить на согласование!', 'Ошибка', 'error');
                    } else if(action == 2) {
                        showNotification('Не удалось отклонить!', 'Ошибка', 'error');
                    } else if(action == 3) {
                        showNotification('Не удалось принять!', 'Ошибка', 'error');
                    } else if(action == 5) {
                        showNotification('Не удалось выполнить запрос!', 'Ошибка', 'error');
                    }
                }
            }
        });
    }
});