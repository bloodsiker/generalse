<?php
namespace Umbrella\components\Api;

class Response
{

    /**
     * return json response
     * @param $status
     * @param $status_message
     * @param $data
     */
    public static function responseJson($data, $status = 200, $status_message = null)
    {
        header("Content-Type: application/json; charset=utf-8");
        header('Access-Control-Allow-Origin: *');
        header("HTTP/1.1 $status $status_message");
        http_response_code($status);

        if($status != 200){
            $response['status_message'] = $status_message;
        } else {
            $response['data'] = $data;
        }

        echo json_encode($response);
    }


    /**
     * return array json response
     * @param $data
     * @param int $status
     * @param null $status_message
     */
    public static function responseArrayJson($data, $status = 200, $status_message = null)
    {
        header("Content-Type: application/json; charset=utf-8");
        header('Access-Control-Allow-Origin: *');
        header("HTTP/1.1 $status $status_message");
        http_response_code($status);

        if($status != 200){
            $response['status_message'] = $status_message;
        } else {
            $response = $data;
        }

        echo json_encode($response);
    }
}