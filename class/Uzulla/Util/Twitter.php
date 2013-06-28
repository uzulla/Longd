<?php
namespace Uzulla\Util;

class Twitter
{
    private static $_instance = null;
    protected $_oauth_token = null;
    protected $_oauth_token_secret = null;

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public static function getCb()
    {
        return \Codebird\Codebird::getInstance();
    }

    public static function setConsumerKey($key, $secret)
    {
        \Codebird\Codebird::setConsumerKey($key, $secret);
    }

    public function setToken($token, $secret)
    {
        $this->_oauth_token        = $token;
        $this->_oauth_token_secret = $secret;
    }

    static public function getMe($user_value)
    {
        $cb = self::getCb();
        $cb->setToken($user_value['twitter_oauth_token'], $user_value['twitter_oauth_token_secret']);

        try{
            $res = $cb->account_verifyCredentials();
            if($res->httpstatus != 200){
                throw new \Exception("Twitter api response {$res->httpstatus} -> {$res->errors[0]->message}");
            }
        }catch(\Exception $e){
            throw $e;
        }

        return $res;
    }

    static public function getByScreenName($user_value, $screen_name)
    {
        $cb = self::getCb();
        $cb->setToken($user_value['twitter_oauth_token'], $user_value['twitter_oauth_token_secret']);

        try{
            $res = $cb->users_show(['screen_name'=>$screen_name]);
            if($res->httpstatus != 200){
                throw new \Exception("Twitter api response {$res->httpstatus} -> {$res->errors[0]->message}");
            }
        }catch(\Exception $e){
            throw $e;
        }

        return $res;
    }

    static public function getByTwitterId($user_value, $twitter_id)
    {
        plog([$user_value, $twitter_id]);
        $cb = self::getCb();
        $cb->setToken($user_value['twitter_oauth_token'], $user_value['twitter_oauth_token_secret']);

        try{
            $res = $cb->users_show(['user_id'=>$twitter_id]);
            if($res->httpstatus != 200){
                throw new \Exception("Twitter api response {$res->httpstatus} -> {$res->errors[0]->message}");
            }
        }catch(\Exception $e){
            throw $e;
        }

        return $res;
    }


}
