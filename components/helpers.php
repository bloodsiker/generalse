<?php

/**
 * Helpers functions
 */

if(!function_exists('app_path')){
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
    function views_path($path){
        $dir = ROOT . '/views/';
        if(is_dir($dir)){
            return $dir . $path ;
        } else {
            throw new Exception('This directory does not exist');
        }
    }
}