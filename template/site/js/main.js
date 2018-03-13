// business form ajax //////////

$(document).ready(function () {

    $('#business-send').click(function () {
        $('#business-form').foundation('validateForm');
        if ($('input').hasClass('is-invalid-input')) {

        } else {
            console.log('ff');

            $("form").submit(function () {

                var $that = $(this);

                $.ajax({
                    type: "POST",
                    data: {
                        email: $("#email").val(),
                        subject: $("#subject").val(),
                        text: $("#text").val()
                    },
                    url: "/contact_form"
                }).done(function (d) {
                    $('#business-form').trigger("reset");
                    $('#senq').foundation('open');
                });
                return false;
            });
        }
    });

// career form ajax //////////

    $('#career-send1').click(function () {
        $('#career-form1').foundation('validateForm');
        if ($('input').hasClass('is-invalid-input')) {

        } else {
            $("form").submit(function () {
                let newForm = $(this).serializeObject(); // получение данных в объекте
                let json = JSON.stringify(newForm); // json
                $.ajax({
                    url: "/career",
                    type: "POST",
                    data: {json : json},
                    cache: false,
                    success: function () {
                        $('#career-form1').trigger("reset");
                        $('#senq').foundation('open');
                    }
                });
                return false;
            });
        }
    });


    $('#career-send2').click(function () {

        $('#career-form2').foundation('validateForm');
        if ($('input').hasClass('is-invalid-input')) {
        } else {
            $("form").submit(function () {
                let newForm = $(this).serializeObject(); // получение данных в объекте
                let json = JSON.stringify(newForm); // json
                $.ajax({
                    url: "/career",
                    type: "POST",
                    data: {json : json},
                    cache: false,
                    success: function () {
                        $('#career-form2').trigger("reset");
                        $('#senq').foundation('open');
                    }
                });
                return false;
            });
        }
    });

    $('#career-send3').click(function () {
        $('#career-form3').foundation('validateForm');
        if ($('input').hasClass('is-invalid-input')) {
            return false;
        } else {
            $("form").submit(function () {
                let newForm = $(this).serializeObject(); // получение данных в объекте
                let json = JSON.stringify(newForm); // json
                $.ajax({
                    url: "/career",
                    type: "POST",
                    data: {json : json},
                    cache: false,
                    success: function () {
                        $('#career-form3').trigger("reset");
                        $('#senq').foundation('open');
                    }
                });
                return false;
            });
        }
    });
});

$('#sign-up-form').submit(function(e) {
    e.preventDefault();
    if ($('#sign-up-form input').hasClass('is-invalid-input')) {
        return false;
    } else {
        let newForm = $(this).serializeObject(); // получение данных в объекте
        let json = JSON.stringify(newForm); // json
        console.log(json); // send to server json AJAX
        $.ajax({
            url: "/sign_up",
            type: "POST",
            data: {json : json},
            cache: false,
            success: function (response) {
                let obj = jQuery.parseJSON(response);
                console.log(response);
                if(obj.code == 200){
                    setTimeout(function () {
                        $('#sign-up').foundation('close');
                        $('#sign-up-form').trigger("reset");
                    }, 500);
                    var html_error = "<div class='umbrella-alert'>"
                        + obj.message
                        + "</div>";

                    $('body').append(html_error);

                    setTimeout(remove_elem, 10000);
                } else if(obj.code == 503){

                    var html_error = "<div class='umbrella-alert'>"
                        + obj.message
                        + "</div>";

                    $('body').append(html_error);

                    setTimeout(remove_elem, 10000);
                }
            }
        });
        return false;
    }
});


// auth

$('#open-auth').click(function () {
    $('.auth').toggle();
});
$(document).on('click', function (e) {
    if ($(e.target).closest("#open-auth").length === 1 || $(e.target).closest(".auth").length === 1) {
        e.stopPropagation();
    } else $('.auth').hide();

});

if (location.href === "http://generalse.loc/" || location.href === "http://generalse.loc/#") {
    $('header').addClass("home-menu");
}

var w = $(window).width();

if (w <= 769) {
    $('.geography').removeAttr('data-parallax');
}



// Закрываем окно umbrella_down
$('body').on('click', '.btn-down', function (e) {
    $('#umbrella-down').remove();
});

$(document).ready(function () {
    var html_down = '';

    $('body').on('click', '#login_umbrella', function (e) {

        var error_login = false;

        var login = $('.login_umbrella').val();
        if (login.length == "") {
            error_login = true;
            $(".login_umbrella").css('border-color', 'red');
        } else {
            $(".login_umbrella").css('border-color', 'rgb(169, 169, 169)');
        }

        var password = $('.password_umbrella').val();
        if (password.length == "") {
            error_login = true;
            $(".password_umbrella").css('border-color', 'red');
        } else {
            $(".password_umbrella").css('border-color', 'rgb(169, 169, 169)');
        }

        if (error_login == false) {

            var data = "action=login&login=" + login + "&password=" + password;

            $.ajax({
                url: "/auth",
                type: "POST",
                data: data,
                cache: false,
                success: function(data){
                    var obj = jQuery.parseJSON(data);
                    if(obj.code == 1){

                        var html_error = "<div class='umbrella-alert'>"
                            + "<span>Не верные данные для входа в кабинет!</span>"
                            + "</div>";

                        $('body').append(html_error);

                        setTimeout(remove_elem, 10000);

                        $(".password_umbrella").val("");

                    } else if(obj.code == 3) {

                        var html_mess = "<div class='umbrella-alert'>"
                                        + "<span>" + obj.log + "</span>"
                                        + "</div>";

                        $('body').append(html_mess);

                        setTimeout(remove_elem, 10000)

                    } else {
                        window.location = obj.log;
                    }
                }
            });
            return false;
        }
        e.preventDefault();
    });
});

let remove_elem = function () {
    $('.umbrella-alert').fadeOut(1500, function () {
        $('.umbrella-alert').remove();
    });
};



// Sign Up
$('#sign-up-form').submit(function(e) {
    e.preventDefault();
    if ($('#sign-up-form input').hasClass('is-invalid-input')) {
        return false;
    } else {
        let newForm = $(this).serializeObject(); // получение данных в объекте
        let json = JSON.stringify(newForm); // json
        console.log(json); // send to server json AJAX
        $.ajax({
            url: "/sign_up",
            type: "POST",
            data: {json : json},
            cache: false,
            success: function (response) {
                console.log(response);
                if(response == 1){
                    setTimeout(function () {
                        $('#sign-up').foundation('close');
                        $('#sign-up-form').trigger("reset");
                    }, 500);
                    var html_error = "<div class='umbrella-alert'>"
                        + "<span>Ваша заявка отправлена на рассмотрение. Наш менеджер свяжется с Вами в ближайшее время</span>"
                        + "</div>";

                    $('body').append(html_error);

                    setTimeout(remove_elem, 10000);
                } else if(response == 0){

                    var html_error = "<div class='umbrella-alert'>"
                        + "<span>Произошла ошибка при отправке заявки. Свяжитесь пожалуйста с нами по адресу <a href='mailto:sales@generalse.com'>sales@generalse.com</a></span>"
                        + "</div>";

                    $('body').append(html_error);

                    setTimeout(remove_elem, 10000);
                }
            }
        });
        return false;
    }
});
