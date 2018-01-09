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
        'server_down' => 'Извините, Umbrella на техническом облуживании!<br> Сервис будет доступен в 08.11.2017 в 09:27 по Киеву',
        'project_denied' => 'Для данного аккаунта нету доступа в Umbrella',
        'login_false' => 'Incorrect data entry',
        'user_is_active' => 'Доступ к данному аккаунту закрыт!',
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
     * Включение/выключение режима отладки
     */
    'debug' => true,


    /**
     * Устанавливаем временную зону
     */
    'timezone' => 'Europe/Kiev',

];