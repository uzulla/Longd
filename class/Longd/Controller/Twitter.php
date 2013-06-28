<?php
namespace Longd\Controller;

use Longd\Model\UserAccount as UserAccount;

class Twitter {

    static function authStart($app)
    {
        $cb = \Codebird\Codebird::getInstance();
        $reply = $cb->oauth_requestToken(array(
            'oauth_callback' => $app->request()->getUrl().$app->urlFor('twitter_callback')
        ));
        $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);

        $_SESSION['oauth_token'] = $reply->oauth_token;
        $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
        $_SESSION['oauth_verify'] = true;

        $auth_url = $cb->oauth_authenticate();
        $app->redirect($auth_url);
    }

    static function callback($app)
    {
        if( !isset($_GET['oauth_verifier']) || !isset($_SESSION['oauth_verify']) ){
            return $app->redirect('login'); // なにかおかしい or ログインキャンセルしたので
        }
        unset($_SESSION['oauth_verify']);

        //get token
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $reply = $cb->oauth_accessToken(['oauth_verifier' => $_GET['oauth_verifier']]);

        //get user info
        $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
        try{
            $user = $cb->account_verifyCredentials();
        }catch(\Exception $e){
            throw $e;
        }

        //load or create user.
        $ua = UserAccount::getByTwitterId($user->id_str);
        if(empty($ua)){ $ua = new UserAccount(); }
        $ua->updateByTwitterRes($user);
        $ua->val('twitter_oauth_token', $reply->oauth_token);
        $ua->val('twitter_oauth_token_secret', $reply->oauth_token_secret);
        $ua->saveItem();

        $_SESSION['user_account_id'] = $ua->val('id');

        plog(['user login', $user]);

        $app->redirect($app->urlFor('mypage'));
    }


}