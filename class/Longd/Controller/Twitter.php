<?php
namespace Longd\Controller;

use Longd\Model\UserAccount;

class Twitter {

    static function authStart($app){
        $cb = \Codebird\Codebird::getInstance();
        $reply = $cb->oauth_requestToken(array(
            'oauth_callback' => $app->request()->getUrl().$app->urlFor('twitter_callback')
        ));
        $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);

        $_SESSION['oauth_token'] = $reply->oauth_token;
        $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
        $_SESSION['oauth_verify'] = true;

        $auth_url = $cb->oauth_authorize();
        $app->redirect($auth_url);
    }

    static function callback($app){
        if( !isset($_GET['oauth_verifier']) || !isset($_SESSION['oauth_verify']) ){
            return $app->redirect('twitter_auth_start');
        }
        unset($_SESSION['oauth_verify']);

        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $reply = $cb->oauth_accessToken(['oauth_verifier' => $_GET['oauth_verifier']]);

        $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
        try{
            $user = $cb->account_verifyCredentials();
        }catch(\Exception $e){
            $app->halt(500, 'Twitter API fail');
        }

        error_log(print_r($user,1));

        $ua = \Longd\Model\UserAccount::getByTwitterId($user->id_str);
        if(empty($ua)){
            $ua = new \Longd\Model\UserAccount();
        }

        $ua->val('twitter_id', $user->id_str);
        $ua->val('profile_image_url', $user->profile_image_url);
        $ua->val('screen_name', $user->screen_name);
        $ua->val('display_name', $user->name);
        $ua->val('twitter_oauth_token', $reply->oauth_token);
        $ua->val('twitter_oauth_token_secret', $reply->oauth_token_secret);
        $ua->saveItem();

        $_SESSION['user_account_id'] = $ua->val('id');

        $app->redirect($app->urlFor('mypage'));
    }


}