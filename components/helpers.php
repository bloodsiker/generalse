<?php

/**
 * Helpers functions
 */

if(!function_exists('app_path')){
    /**
     * @param $path
     *
     * @return string
     * @throws Exception
     */
    function app_path($path){
        $dir = ROOT . '/app/';
        if(is_dir($dir)){
            return $dir . $path ;
        } else {
            throw new Exception('This directory does not exist');
        }
    }
}

if(!function_exists('components_path')){
    /**
     * @param $path
     *
     * @return string
     * @throws Exception
     */
    function components_path($path){
        $dir = ROOT . '/components/';
        if(is_dir($dir)){
            return $dir . $path;
        } else {
            throw new Exception('This directory does not exist');
        }
    }
}

if(!function_exists('views_path')){
    /**
     * @param $path
     *
     * @return string
     * @throws Exception
     */
    function views_path($path){
        $dir = ROOT . '/views/';
        if(is_dir($dir)){
            return $dir . $path ;
        } else {
            throw new Exception('This directory does not exist');
        }
    }
}


if(!function_exists('upload_path')){
    /**
     * @param $path
     *
     * @return string
     * @throws Exception
     */
    function upload_path($path){
        $dir = ROOT . '/upload/';
        if(is_dir($dir)){
            return $dir . $path ;
        } else {
            throw new Exception('This directory does not exist');
        }
    }
}


if(!function_exists('config')){
    /**
     * @param $file
     *
     * @return mixed
     * @throws Exception
     */
    function config($file){
        $dir = ROOT . '/config/';
        $config_file = $dir . $file . '.php';
        if(is_dir($dir) && file_exists($config_file)){
            return require($config_file);
        } else {
            throw new Exception('This file does not exist');
        }
    }
}

