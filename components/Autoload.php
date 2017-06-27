<?php
/**
 * Автоподгрузка класов
 * @param $class_name
 */
function autoloadClass($class_name)
{
    $array_paths = array(
        '/app/',
        '/app/Services/',
        '/models/',
        '/components/',
        '/components/Db/',
        '/components/cron/',
        '/components/cron/task/',
        '/controllers/umbrella/',
        '/controllers/umbrella/crm/',
    );

    foreach ($array_paths as $path) {

        $path = ROOT . $path . $class_name . '.php';

        if (is_file($path)) {
            include_once $path;
        }
    }
}

spl_autoload_register('autoloadClass', true, true);
