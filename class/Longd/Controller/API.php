<?php
namespace Longd\Controller;

use Uzulla\CFEDb2;
use Uzulla\Util as Util;

class API {

    static function screen_name_exist($app)
    {
        try{
            $reply = Util\Twitter::getByScreenName(
                $app->stash['user'],
                $app->request()->post('screen_name'));
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

        Util\Slim::responseJson($app, $res);
    }

    static function get_message($app)
    {
        if(!isset($app->stash['user'])){
            throw new \Exception('not login');
        }

        $limit = ($app->request()->get('limit')) ? $app->request()->get('limit') : 10;
        if($limit > 1000){ throw new \Exception('too big limit'); }
        $offset = ($app->request()->get('offset')) ? $app->request()->get('offset') : 0;
        $user_account_id = $app->stash['user']['id'];

        $message_list = CFEDb2::simpleQuery(
            'SELECT
                message.*,
                user_account.screen_name,
                user_account.profile_image_url
            FROM message JOIN user_account
                ON message.from_user_account_id=user_account.id
            WHERE  to_user_account_id=:to_user_account_id
            LIMIT :limit OFFSET :offset',
            [
                'limit'=>$limit,
                'offset'=>$offset,
                'to_user_account_id'=>$user_account_id
            ]);

        Util\Slim::responseJson($app, $message_list);
    }

}