<?php
namespace Longd\Controller;

class Mypage {

    static function index($app){
        $app->render('Mypage/index.html');
    }

}