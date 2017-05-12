var json = [{
    "purchase_number": "1111111",
    "service_order": "1231231212",
    "stock": "BAD",
    "date": "17-12-2016",
    "part_number": "123123",
    "quantity": "",
    "price": "0",
    "desription": "Lenovo a 1000",
    "status": "not accepted",
    "author": "parse in session"
}, {
    "purchase_number": "222222",
    "service_order": "43543531",
    "stock": "BAD",
    "date": "17-12-2016",
    "part_number": "123123",
    "price": "0",
    "quantity": "2",
    "desription": "Lenovo a 2000",
    "status": "not accepted",
    "author": "parse in session"
}];

// var json = JSON.parse(data); //AJAX parse data

var CHECKOUTS = json; // КОРЗИНА

renderCheckouts();
// рендер корзин
function renderCheckouts() {
    $('.checkout tbody').html(function() {
        return CHECKOUTS.map(function(el, index) {
            if (el.status == 'not accepted') {
                var status = 'background-color: rgba(244, 67, 54, 0.5);'
            } else {
                var status = 'background-color: rgba(76, 175, 80, 0.5);'
            }
            return `<tr data-purchase="` + el.purchase_number + `" data-index='` + index + `'>
                        <td>` + el.purchase_number + `</td>
                        <td>` + el.service_order + `</td>
                        <td>` + el.stock + `</td>
                        <td>` + el.date + `</td>
                        <td>` + el.part_number + `</td>
                        <td>` + el.desription + `</td>
                        <td>` + el.price + `</td>
                        <td>` + el.quantity + `</td>
                        <td style='` + status + `'>` + el.status + `</td>
                    </tr>`
        });
    });
    $('table').tablesort();
}

// открыть окно добавления корзины
$('body').on('click', '#add-checkout-button', function() {
    $('#add-checkout-modal').foundation('open');
    hideAllInput();
});

//PART NUMBER SEARCH
$('[name="part_number"]').keyup(function(e) {
    //test search
    $('.name-product').text(function() {
        if (e.target.value == '111222') {
            return 'Lenovo a 1000'
        } else $('.name-product').text('');
    })
});

function hideAllInput() {
    $('[name="service_order"], [name="part_number"], [name="price"], [name="quantity"]')
        .removeAttr('required')
        .parent().hide();
    $('.name-product').text('');
};
$('#stock').change(function(e) {
    switch (e.target.value) {
        case 'BAD':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            break
        case 'Local Source':
            hideAllInput();
            $('[name="service_order"], [name="part_number"], [name="price"]')
                .attr('required', '')
                .parent().show();
            break
        case 'Not Used':
            hideAllInput();
            $('[name="service_order"], [name="part_number"]')
                .attr('required', '')
                .parent().show();
            break
        case 'Restored':
            hideAllInput();
            $('[name="part_number"], [name="quantity"]')
                .attr('required', '')
                .parent().show();
            break
        case 'Restored Bad':
            hideAllInput();
            $('[name="part_number"], [name="quantity"]')
                .attr('required', '')
                .parent().show();
            break
        default:

    }
})


// добавить корзину
$('#add-checkout-form').submit(function(e) {
    e.preventDefault();
    if ($('#add-checkout-form input').hasClass('is-invalid-input')) { // проверка на валидность
        return false;
    } else {
        var newCheckout = $(this).serializeObject(); // получение данных в объекте
        newCheckout.status = 'not accepted';
        newCheckout.date = new Date().getDate().toString() + '-' + (new Date().getMonth() + 1).toString() + '-' + new Date().getFullYear().toString();
        newCheckout.author = 'parse in session';
        //newCheckout.purchase_number = '11111';
        newCheckout.desription = $('.name-product').text();
        CHECKOUTS.push(newCheckout); // TEST
        var json = JSON.stringify(newCheckout); // json
        console.log(json); // send to server json AJAX
        renderCheckouts(); // перерендерить и сохранить в кэш
        $(this)[0].reset();
        $('#add-checkout-modal').foundation('close');

    }
    e.preventDefault();

});

$("#date-start, #date-end").datepicker({
    buttonText: "Choose",
    regional: 'ru',
    dateFormat: 'dd-mm-yy'
});
