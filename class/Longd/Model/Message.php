<?php
namespace Longd\Model;

class Message extends \Uzulla\CFEDb2{

    static $tablename = 'message';
    static $pkeyname = 'id';

    public function __construct() {
        //settings colmun, and default value;
        $this->values['id'] = null;
        $this->values['to_user_account_id'] = null;
        $this->values['from_user_account_id'] = null;
        $this->values['message'] = null;
        $this->values['is_open'] = 0;
        $this->values['created_at'] = null;
        $this->values['updated_at'] = null;
        parent::__construct();
    }

    static function getByFromTwitterId($twitter_id){
        return static::getBySome('from_twitter_id', $twitter_id);
    }

    static function countMyInboxMessage($user_value){
        $res = Message::simpleQueryOne(
            'SELECT count(*) as count FROM message WHERE to_user_account_id = :user_account_id',
            ["user_account_id"=>$user_value['id']]
        );

        return $res['count'];
    }

}
