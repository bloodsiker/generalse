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

    $('.dropdown').on('show.bs.dropdown', event => event.preventDefault());

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
    })()

});




