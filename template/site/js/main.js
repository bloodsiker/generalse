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
                var $that = $(this),
                    formData = new FormData($that.get(0));
                $.ajax({
                    type: "POST",
                    url: "send_career.php",
                    processData: false,
                    contentType: false,
                    data: formData,
                    dataType: 'json'
                }).done(function () {
                    $('#career-form1').trigger("reset");
                    $('#senq').foundation('open');
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
                var $that = $(this),
                    formData = new FormData($that.get(0));
                $.ajax({
                    type: "POST",
                    url: "send_career.php",
                    processData: false,
                    contentType: false,
                    data: formData,
                    dataType: 'json'
                }).done(function (data) {
                    $('#career-form2').trigger("reset");
                    $('#senq').foundation('open');
                });
                return false;
            });

        }
    });

    $('#career-send3').click(function () {
        $('#career-form3').foundation('validateForm');
        if ($('input').hasClass('is-invalid-input')) {

        } else {

            $("form").submit(function () {
                var $that = $(this),
                    formData = new FormData($that.get(0));
                $.ajax({
                    type: "POST",
                    url: "send_career.php",
                    processData: false,
                    contentType: false,
                    data: formData,
                    dataType: 'json'
                }).done(function () {
                    $('#career-form3').trigger("reset");
                    $('#senq').foundation('open');
                });
                return false;
            });

        }
    });

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

            // html_down = "<div id='umbrella-down'>"
            //                 + "<h3>В данный момент Umbrella не доступна!</h3>"
            //                 + "<p>Сервис на техническом облуживании! <br> В ближайшее время вы сможете продолжить работу ^_^)</p>"
            //                 + "<button class='btn-down'>Закрыть</button>"
            //                 + "</div>";
            //
            // $('body').append(html_down);


            var data = "action=login&login=" + login + "&password=" + password;

            $.ajax({
                url: "/auth",
                type: "POST",
                data: data,
                cache: false,
                success: function(data){
                    var obj = jQuery.parseJSON(data);
                    if(obj.code == 1){
                        var html = "<p class='error_data'>" + obj.log + "</p>";

                        if($('p').is('.error_data')){
                            return false;
                        } else {
                            $('.form_title').after(html);
                        }
                        $(".password_umbrella").val("");

                    } else {
                        window.location = obj.url;
                    }
                }
            });

            return false;
        }
        e.preventDefault();
    });
});
