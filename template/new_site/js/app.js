$(document).ready(function () {

    $('.phone').mask('(000) 000-00-00');


    // text animations

    $(document).on('mouseenter', '.nav-link', function () {
        $(this).find('.dropdown-menu').addClass('show');
    });

    $(document).on('scroll', event => {
        if ($(document).scrollTop() > 100 && $(document).scrollTop() > 99) {
            $('.navbar')
                .addClass('mini');
        } else {
            $('.navbar')
                .removeClass('mini');
        }
    });

    $('.dropdown:not(.dropdown-not-hover)').on('show.bs.dropdown', event => event.preventDefault());

    if ($(document).find('.tlt').length) {
        $('.tlt').textillate({
            minDisplayTime: 100,
            in: {
                effect: 'fadeInLeftBig',
                delayScale: 0.3,

            }
        });
    };


    // number animations

    (function () {
        if ($(document).find('.numbers-box').length) {
            let blockTop = $('.numbers-box').offset().top;
            let CountUpFlag = 0;
            let $window = $(window);
            $window.on('load scroll', function () {
                let top = $window.scrollTop();
                let height = $window.height();
                if (top + height >= blockTop && CountUpFlag == 0) {
                    CountUp();
                    CountUpFlag = 1;
                }
            });

            function CountUp() {
                $('.number').each((index, element) => {
                    $(element).animateNumber({number: +$(element).data('number') || 0}, 2000);
                });
            }
        }

    })();


    //map

    (function () {
        const mapOption = {
            hoverColor: '#c74437',
            borderColor: '#fff',
            borderOpacity: 0.25,
            borderWidth: 0.5,
            color: '#5b7489',
            enableZoom: false,
            backgroundColor: null,
            hoverOpacity: 0.7,
            selectedColor: '#C8EEFF',
            values: sample_data,
            scaleColors: ['#C8EEFF', '#c74437'],
            normalizeFunction: 'polynomial',
            showTooltip: true,
            onLabelShow: function (event, label, code) {
                if (tooltips[code]) {
                    label.text(tooltips[code]);
                } else {
                    event.preventDefault();
                }
            },
            onRegionOver: function(event, code, label) {
                if (!tooltips[code]) {
                    event.preventDefault();
                } else {
                }
            },
        };


        if ($(document).find('#vmap').length) {
            $('#vmap').vectorMap({
                map: 'europe_en',
                ...mapOption,
                onRegionClick: function (event, code, region) {
                    if (Object.keys(sample_data).indexOf(code) !== -1) {
                        $('#country-name').html(`${region} (${code.toUpperCase()})`);
                        $('html, body')
                            .animate({
                                scrollTop: $(document).find('[data-country="' + code + '"]').offset().top - 120
                            }, 1000);
                    } else {
                        event.preventDefault();
                    }
                }
            });
        }
        if ($(document).find('#world-map').length) {
            $('#world-map').vectorMap({
                map: 'europe_en',
                ...mapOption,
                onRegionClick: event => event.preventDefault()
            });
        }

    })();


    //certificates

    (function () {
        lightbox.option({
            'resizeDuration': 100,
            'wrapAround': true,
            fadeDuration: 300
        })

    })();

    //link-to-scroll

    (function () {
        $(document).on('click', '[data-scroll]', e => {
            const scrollTo = id => {
                $('html, body').animate({
                    scrollTop: $(document).find('[data-scroll-div="' + id + '"]').offset().top - 120
                }, 1000);
            };
            if (e.target && e.target.className === 'services-head-link') {
                scrollTo($(e.target).data('scroll-link'));
            } else {
                scrollTo($(e.target).parents('[data-scroll]').data('scroll-link'));
            }
        })
    })();




    $(document).ready(function () {
        $('#form-auth').on('click', '#login_umbrella', function (e) {

            let error_login = false;

            let login = $('.login_umbrella').val();
            if (login.length == "") {
                error_login = true;
                $(".login_umbrella").css({'border-color':'red', 'background': '#ff00001a'});
            } else {
                $(".login_umbrella").removeAttr('style');
            }

            let password = $('.password_umbrella').val();
            if (password.length == "") {
                error_login = true;
                $(".password_umbrella").css({'border-color':'red', 'background': '#ff00001a'});
            } else {
                $(".password_umbrella").removeAttr('style')
            }

            if (error_login == false) {

                let lang = $('#form-auth').find("input[name='lang']").val();
                //let data = "action=login&login=" + login + "&password=" + password;

                $.ajax({
                    url: "/auth",
                    type: "POST",
                    data: {action: 'login', login: login, password: password, lang: lang},
                    cache: false,
                    success: function(data){
                        let obj = jQuery.parseJSON(data);
                        if(obj.code == 1){
                            showNotification(obj.log, 'error');
                            $(".password_umbrella").val("");
                        } else if(obj.code == 3) {
                            showNotification(obj.log, 'warning');
                        } else {
                            window.location = '/' + obj.log;
                        }
                    }
                });
                return false;
            }
            e.preventDefault();
        });
    });


    (function( $ ){

        $(function() {

            $('#sign-up-form').each(function(){
                let form = $(this),
                    btn = form.find('#btn-sign-up');

                form.find('.required').addClass('empty_field');

                // Функция проверки полей формы
                function checkInput(){
                    form.find('.required').each(function(){
                        if($(this).val() != ''){
                            $(this).removeClass('empty_field');
                        } else {
                            $(this).addClass('empty_field');
                        }
                    });
                }

                // Функция подсветки незаполненных полей
                function lightEmpty(){
                    form.find('.empty_field').css({'border-color':'red', 'background': '#ff00001a'});
                    setTimeout(function(){
                        form.find('.empty_field').removeAttr('style');
                    },2000);
                }

                // Проверка в режиме реального времени
                setInterval(function(){
                    checkInput();
                    let sizeEmpty = form.find('.empty_field').length;
                    // Вешаем условие-тригер на кнопку отправки формы
                    if(sizeEmpty > 0){
                        if(btn.hasClass('disabled')){
                            return false
                        } else {
                            btn.addClass('disabled')
                        }
                    } else {
                        btn.removeClass('disabled')
                    }
                },500);

                // Событие клика по кнопке отправить
                btn.click(function(){
                    if($(this).hasClass('disabled')){
                        lightEmpty();
                        return false
                    } else {
                        let newForm = form.serializeObject(); // получение данных в объекте
                        let json = JSON.stringify(newForm); // json
                        $.ajax({
                            url: "/sign_up",
                            type: "POST",
                            data: {json : json},
                            cache: false,
                            success: function (response) {
                                console.log(response);
                                if(response == 1){
                                    setTimeout(function () {
                                        $('#registrationModal').modal('hide');
                                        $('#thank').modal('show');
                                        $('#sign-up-form').trigger("reset");
                                    }, 500);
                                    $('.thank-container').html('<h5>Ваша заявка отправлена на рассмотрение. Наш менеджер свяжется с Вами в ближайшее время</h5>');
                                } else if(response == 0){
                                    $('.thank-container').html('<h5>Произошла ошибка при отправке заявки. Свяжитесь пожалуйста с нами по адресу <a href=\'mailto:sales@generalse.com\'>sales@generalse.com</a></h5>');
                                }
                            }
                        });
                        return false;
                    }
                });
            });
        });

    })( jQuery );


    toastr.options.timeOut = '15000';
    let showNotification = function (message, type) {
        switch (type) {
            case 'success':
                toastr.success(message);
                break;
            case 'error':
                toastr.error(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            case 'info':
                toastr.info(message);
                break;
            default:
                toastr.warning('Не известная ошибка!');
        }
    };
});


// Send email with suppliers form
$("#form-suppliers").submit(function (e) {
//$('#send-suppliers').on('click', function (e) {
    e.preventDefault();
    let error = false;

    if ($("input[name='fio']").val() == "") {
        error = true;
        $("input[name='fio']").css({'border-color':'red', 'background': '#ff00001a'});
    } else {
        $("input[name='fio']").removeAttr('style');
    }

    if ($("input[name='company']").val() == "") {
        error = true;
        $("input[name='company']").css({'border-color':'red', 'background': '#ff00001a'});
    } else {
        $("input[name='company']").removeAttr('style');
    }

    if ($("input[name='email']").val() == "") {
        error = true;
        $("input[name='email']").css({'border-color':'red', 'background': '#ff00001a'});
    } else {
        $("input[name='email']").removeAttr('style');
    }

    if (error == false) {

        let $that = $(this),
            formData = new FormData($that.get(0));

        $.ajax({
            url: "/ru/new/suppliers/send_form",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (response) {
                console.log(response);
                $('#thank').modal('show');
                $('#form-suppliers').trigger("reset");
            }
        });
        return false;
    }
});




