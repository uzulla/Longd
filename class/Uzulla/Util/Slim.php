<?php
namespace Uzulla\Util;

class Slim
{
    static function responseJson($app, $data)
    {
        $response = $app->response();
        $response['Content-Type'] = 'application/json; charset=utf-8' ;
        $response['Pragma'] = 'no-cache';
        $response['Cache-Control'] = 'no-store';
        $response['X-frame-options'] = 'DENY';
        $response['X-Content-Type-Options'] = 'nosniff';
        $response->body(json_encode($data), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
}
