<?php
namespace Longd\Model;

class Friend extends \Uzulla\CFEDb2{

    static $tablename = 'user_account';
    static $pkeyname = 'id';

    public function __construct() {
        //settings colmun, and default value;
        $this->values['id'] = null;
        $this->values['from_twitter_id'] = null;
        $this->values['to_twitter_id'] = null;
        $this->values['to_screen_name'] = null;
        $this->values['to_display_name'] = null;
        $this->values['to_profile_image_url'] = null;
        $this->values['created_at'] = null;
        $this->values['updated_at'] = null;
        parent::__construct();
    }

    static function getByFromTwitterId($twitter_id){
        return static::getBySome('from_twitter_id', $twitter_id);
    }

}
