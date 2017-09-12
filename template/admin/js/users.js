// Показываем в модальном окне, дополнительную информацию
$(document).on('click', '.list-user-func', function(e) {
    var user_id = $(this).attr('data-userid');

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