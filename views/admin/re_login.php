<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Umbrella Re-login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Nunito');
        @import url('https://fonts.googleapis.com/css?family=Poiret+One');

        body, html {
            min-height: 100vh;
            background-repeat: no-repeat;    /*background-image: linear-gradient(rgb(12, 97, 33),rgb(104, 145, 162));*/
            background:black;
            position: relative;
        }
        #login-box {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            width: 350px;
            margin: 0 auto;
            border: 1px solid #636262;
            background: rgba(48, 46, 45, .5);
            min-height: 250px;
            padding: 20px;
            z-index: 9999;
        }
        #login-box .logo .logo-caption {
            font-family: 'Poiret One', cursive;
            color: white;
            text-align: center;
            margin-bottom: 0px;
        }
        #login-box .logo .tweak {
            color: #ff5252;
        }
        #login-box .controls {
            padding-top: 30px;
        }
        #login-box .controls input{
            border-radius: 0px;
            background: rgb(98, 96, 96);
            border: 0px;
            color: white;
            font-family: 'Nunito', sans-serif;
        }
        #login-box .controls input:focus {
            box-shadow: none;
        }
        #login-box .btn-custom {
            border-radius: 2px;
            margin-top: 8px;
            border-color: rgba(48, 46, 45, 1);
            color: white;
            font-family: 'Nunito', sans-serif;
        }
        .btn-red {
            background:#ff5252;
        }
        .btn-blue{
            background: #329ede;
        }
        #login-box .btn-red:hover{
            -webkit-transition: all 500ms ease;
            -moz-transition: all 500ms ease;
            -ms-transition: all 500ms ease;
            -o-transition: all 500ms ease;
            transition: all 500ms ease;
            background: rgba(48, 46, 45, 1);
            border-color: #ff5252;
        }
        #login-box .btn-blue:hover{
            -webkit-transition: all 500ms ease;
            -moz-transition: all 500ms ease;
            -ms-transition: all 500ms ease;
            -o-transition: all 500ms ease;
            transition: all 500ms ease;
            background: rgba(48, 46, 45, 1);
            border-color: #329ede;
        }
        #particles-js{
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: 50% 50%;
            position: fixed;
            top: 0px;
            z-index:1;
        }
        .bootstrap-select.btn-group:not(.input-group-btn){
            border-radius: 1px;
            background: rgba(48, 46, 45, .7);
        }
        .bootstrap-select>.dropdown-toggle.bs-placeholder,
        .bootstrap-select>.dropdown-toggle.bs-placeholder:active,
        .bootstrap-select>.dropdown-toggle.bs-placeholder:focus,
        .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
            border-radius: 1px;
            background: rgba(48, 46, 45, .7);
            border: 0px;
            color: white;
            font-family: 'Nunito', sans-serif;
        }
        .error{
            text-align: center;
            color: #ff5252;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div id="login-box">
        <div class="logo">
            <h1 class="logo-caption"><span class="tweak">Re</span>-login</h1>
        </div><!-- /.logo -->
        <div class="controls">
            <div class="error">

            </div>
            <form action="" method="post" id="form-re-login">
                <input type="hidden" name="re-login" value="true" class="form-control" />
                <select name="id_partner" class="form-control selectpicker required" data-live-search="true" <?= $error == true ? 'disabled' : null ?>>
                    <option value=""></option>
                    <?php if(is_array($listPartner)): ?>
                        <?php foreach ($listPartner as $partner): ?>
                            <option <?= ($partner['is_active'] == 0 ? 'disabled' : null) ?> value="<?= $partner['id_user'] ?>"><?= $partner['name_partner'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <button <?= $error == true ? 'disabled' : null ?> class="btn btn-block btn-custom btn-red">Login</button>
                <?php if(\Josantonius\Session\Session::get('re_login')['my_account'] == 1): ?>
                    <a href="/<?= $user->getUrlAfterLogin() ?>" class="btn btn-default btn-block btn-custom btn-blue"> В свой кабинет</a>
                <?php else: ?>
                    <a href="/adm/return_my_account" class="btn btn-default btn-block btn-custom btn-blue"> В свой профиль</a>
                <?php endif; ?>
            </form>
        </div><!-- /.controls -->
    </div><!-- /#login-box -->
</div><!-- /.container -->

<div id="particles-js"></div>

<script src="/template/admin/js/vendor/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
<script>
    $('#form-re-login').submit(function (e) {
        e.preventDefault();
        let error_block = $('.error');
        error_block.text('');
        if($('select[name="id_partner"]').val().length !== 0){
            e.target.submit()
        } else {
            error_block.text('Выбирите пользователя из списка!');
        }
    })
</script>
<script>
    particlesJS('particles-js',
        {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    },
                    "image": {
                        "width": 100,
                        "height": 100
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 5,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 6,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 400,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true,
            "config_demo": {
                "hide_card": false,
                "background_color": "#b61924",
                "background_image": "",
                "background_position": "50% 50%",
                "background_repeat": "no-repeat",
                "background_size": "cover"
            }
        }
    );
</script>
</body>
</html>

