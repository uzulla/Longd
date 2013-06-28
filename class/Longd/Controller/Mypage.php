<?php
namespace Longd\Controller;

class Mypage {
    /**
     * @var \Slim\Slim $app
     */
    static function index($app){
        $total_message_num = \Longd\Model\Message::countMyInboxMessage($app->stash['user']);
        $app->render('Mypage/index.html', [
            'total_message_num' => $total_message_num
        ]);
    }

}