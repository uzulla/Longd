<?php
namespace Longd\Controller;

class Message {

    /**
     * @var \Slim\Slim $app
     */
    static function create($app){
        $app->render('Message/create.html');
    }

    /**
     * @var \Slim\Slim $app
     */
    static function commit($app){
        $r = $app->request();

        //todo save db
        $message = new \Longd\Model\Message();

        $message->val('to_twitter_id', $r->params('tiwtter_id_str'));
        //TODO STARTHERE


        exit;
        $app->redirect('message_complete');
    }

    /**
     * @var \Slim\Slim $app
     */
    static function complete($app){
        $app->render('Message/complete.html');
    }

}