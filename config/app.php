<?php

return [

    /**
     * Название проекта
     */
    'name' => 'Umbrella',


    /**
     * Отключаем сайт
     *  down/up
     */
    'server' => 'up',


    /**
     * При выключенном сайте, показываем пользователю уведомление
     */
    'notification' => [
        'ru' => [
            'server_down' => 'Извините, Umbrella на техническом облуживании! Сервис будет доступен 28.03.2018 в 14:00 по Киеву',
            'project_denied' => 'Для данного аккаунта нету доступа в Umbrella',
            'login_false' => 'Не верные данные для входа в кабинет!',
            'user_is_active' => 'Доступ к данному аккаунту закрыт!',
        ],
        'en' => [
            'server_down' => 'Sorry, Umbrella is on technical maintenance! The service will be available on 08.11.2017 at 09:27 in Kiev',
            'project_denied' => 'This account does not have access to Umbrella',
            'login_false' => 'Incorrect data entry',
            'user_is_active' => 'Access to this account is closed!',
        ]
    ],


    /**
     * Перенаправление пользователей
     */
    'url_redirect' => [
        'user_risk' => '/adm/risks',
    ],


    /**
     * Среда разработки
     * local/production
     */
    'env' => 'local',


    /**
     * Включение/выключение режима отладки true/false
     */
    'debug' => true,


    /**
     * Устанавливаем временную зону
     */
    'timezone' => 'Europe/Kiev',

];