// открыть окно добавления корзины
$('body').on('click', '#add-request-button', function() {
    $('.name-product').text('');
    $('#add-request-modal form')[0].reset();
    $('#add-request-modal').foundation('open');
});

// модальное окно (узнать цену по парт номеру)
$('body').on('click', '#price-button', function() {
    $('.name-product').text('');
    $('#price-modal form')[0].reset();
    $('#price-modal').foundation('open');
});


//PART NUMBER SEARCH
$('[name="part_number"]').keyup(function(e) {
    //ajax e.target.value
    var part_number = e.target.value;

    $.ajax({
        url: "/adm/crm/request/price_part_ajax",
        type: "POST",
        data: {part_number : part_number},
        cache: false,
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(response);
            if(obj.result == 1){
                $("[name='part_number']").removeClass('error_part');
                $('.name-product').text(obj.mName).css('color', '#4CAF50');
                $("[name='price']").val(obj.price);
            } else {
                $('.name-product').text('not found').css('color', 'red');
                $("[name='price']").val('0');
            }
        }
    });

    return false;
});


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