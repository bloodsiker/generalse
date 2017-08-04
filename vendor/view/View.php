<?php

namespace Umbrella\vendor\view;

// Путь до папки с шаблонами
define('VIEWS_BASEDIR', dirname(__FILE__).'/../../views/');

class View {


    /**
     * получить отренедеренный шаблон с параметрами $params
     * @param $template
     * @param array $params
     * @return string
     */
    public function fetchPartial($template, $params = array())
    {
        extract($params);
        ob_start();
        include VIEWS_BASEDIR . $template.'.php';
        return ob_get_clean();
    }


    /**
     * вывести отренедеренный шаблон с параметрами $params
     * @param $template
     * @param array $params
     */
    public function renderPartial($template, $params = array())
    {
        echo $this->fetchPartial($template, $params);
    }



    /**
     * получить отренедеренный в переменную $content layout-а
     * шаблон с параметрами $params
     * @param $template
     * @param array $params
     * @return string
     */
    public function fetch($template, $params = array())
    {
        $content = $this->fetchPartial($template, $params);
        return $this->fetchPartial('layout', array('content' => $content));
    }


    /**
     * вывести отренедеренный в переменную $content layout-а
     * шаблон с параметрами $params
     * @param $template
     * @param array $params
     */
    public function render($template, $params = array())
    {
        echo $this->fetchPartial($template, $params);
    }
}