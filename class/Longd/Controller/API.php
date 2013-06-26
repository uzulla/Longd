<?php
namespace Longd\Controller;

class API {

    static function screen_name_exist($app){

        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken($_SESSION['user']['twitter_oauth_token'], $_SESSION['user']['twitter_oauth_token_secret']);
        try{
            $reply = $cb->users_show(['screen_name'=>$app->request()->post('screen_name')]);
        }catch(\Exception $e){
            $app->halt(500, 'Twitter API fail');
        }

        $res = [
            'status'=> 'ok',
            'data'=> [
                'screen_name_exists'=> 1,
                'screen_name' => $reply->screen_name,
                'profile_image_url' => $reply->profile_image_url,
                'id_str' => $reply->id_str
            ]
        ];

        $response = $app->response();
        $response['Content-Type'] = 'application/json; charset=utf-8' ;
        $response['Pragma'] = 'no-cache';
        $response['Cache-Control'] = 'no-store';
        $response['X-frame-options'] = 'DENY';
        $response['X-Content-Type-Options'] = 'nosniff';
        $response->body(json_encode($res), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

}