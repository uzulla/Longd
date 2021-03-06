<?php
namespace Longd\Model;

class UserAccount extends \Uzulla\CFEDb2{

    static $tablename = 'user_account';
    static $pkeyname = 'id';

    public function __construct() {
        //settings colmun, and default value;
        $this->values['id'] = null;
        $this->values['twitter_id'] = null;
        $this->values['profile_image_url'] = null;
        $this->values['screen_name'] = null;
        $this->values['display_name'] = null;
        $this->values['twitter_oauth_token'] = null;
        $this->values['twitter_oauth_token_secret'] = null;

        $this->values['created_at'] = null;
        $this->values['updated_at'] = null;
        parent::__construct();
    }

    static function getByTwitterId($twitter_id){
        return static::getBySome('twitter_id', $twitter_id);
    }

    public function updateByTwitterRes($user)
    {
        plog($user);
        $this->val('twitter_id', $user->id_str);
        $this->val('profile_image_url', $user->profile_image_url);
        $this->val('screen_name', $user->screen_name);
        $this->val('display_name', $user->name);
    }

}
