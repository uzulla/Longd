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

        $user = $app->stash['user'];
        $to_twitter_id = $r->post('twitter_id_str');

        try{
            $res = \Uzulla\Util\Twitter::getByTwitterId($user, $to_twitter_id);
        }catch (\Exception $e){
            throw $e;
        }

        $to_user = \Longd\Model\UserAccount::getByTwitterId($to_twitter_id);
        if(empty($to_user)){ $to_user = new \Longd\Model\UserAccount(); }

        $to_user->updateByTwitterRes($res);
        $to_user->saveItem();

        $message = new \Longd\Model\Message();
        $message->val('to_user_account_id', $to_user->val('id'));
        $message->val('from_user_account_id', $user['id']);
        $message->val('message', $r->post('message'));
        $message->saveItem();

        $app->redirect($app->urlFor('message_complete'));
    }

}