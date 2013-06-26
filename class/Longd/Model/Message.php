<?php
namespace Longd\Model;

class Message extends \Uzulla\CFEDb2{

    static $tablename = 'message';
    static $pkeyname = 'id';

    public function __construct() {
        //settings colmun, and default value;
        $this->values['id'] = null;
        $this->values['to_twitter_id'] = null;
        $this->values['from_twitter_id'] = null;
        $this->values['message'] = null;
        $this->values['is_open'] = 0;
        $this->values['created_at'] = null;
        $this->values['updated_at'] = null;
        parent::__construct();
    }

    static function getByFromTwitterId($twitter_id){
        return static::getBySome('from_twitter_id', $twitter_id);
    }

}
