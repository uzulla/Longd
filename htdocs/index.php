<?php
require_once('../settings.php');
require_once('../vendor/autoload.php');
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/../class');
spl_autoload_register(function($class) {
    $parts = explode('\\', $class);
    $parts[] = str_replace('_', DIRECTORY_SEPARATOR, array_pop($parts));
    $path = implode(DIRECTORY_SEPARATOR, $parts);
    $file = stream_resolve_include_path($path.'.php');
    if($file !== false) {
        require $file;
    }
});

session_cache_limiter(false);
session_start();

\Uzulla\Util\Twitter::setConsumerKey(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
//\Codebird\Codebird::setConsumerKey(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);

$twigView = new \Slim\Extras\Views\Twig();
\Slim\Extras\Views\Twig::$twigExtensions = array(
    'Twig_Extensions_Slim',
);

$app = new \Slim\Slim([
    'debug' => true,
    'templates.path' => realpath('../templates'),
    'view' => $twigView,
]);

$app->stash = [];

$app->hook( 'slim.before.dispatch', function() use ( $app ) {
    $app->view()->appendData(['BASE_URL' => BASE_URL]);

    if(
        !preg_match('/^\/auth/', $app->request()->getResourceUri()) &&
        !isset($_SESSION['user_account_id'])
    ){
        $app->redirect('/auth/login');
    }else{
        if(isset($_SESSION['user_account_id'])){
            $user = \Longd\Model\UserAccount::getById($_SESSION['user_account_id'])->values;
            $app->view()->appendData(['user' => $user ]);
            $app->stash['user'] = $user;
        }
    }

});

$app->get('/', function () use ($app) {
    if($_SESSION['user_account_id']){
        $app->redirect('/mypage');
    }else{
        $app->redirect('/auth/login');
    }
})->name('root');

$app->get('/auth/login', function () use ($app) {
    if(isset($_SESSION['user_account_id'])){
        $app->redirect('/mypage');
    }
    $app->render('Auth/Login.html');
})->name('login');

$app->get('/auth/logout', function () use ($app) {
    $_SESSION = [];
    $app->redirect('root');
})->name('logout');

$app->get('/mypage', function () use ($app) {
    \Longd\Controller\Mypage::index($app);
})->name('mypage');

$app->get('/message/create', function () use ($app) {
    \Longd\Controller\Message::create($app);
})->name('message_create');

$app->post('/message/commit', function () use ($app) {
    \Longd\Controller\Message::commit($app);
})->name('message_commit');

$app->get('/message/complete', function () use ($app) {
    $app->render('Message/complete.html');
})->name('message_complete');

$app->get('/auth/twitter/start', function () use ($app) {
    \Longd\Controller\Twitter::authStart($app);
})->name('twitter_auth_start');

$app->get('/auth/twitter/callback', function () use ($app) {
    \Longd\Controller\Twitter::callback($app);
})->name('twitter_callback');


//-- API
$app->post('/api/twitter/screen_name_exist', function () use ($app) {
    \Longd\Controller\API::screen_name_exist($app);
})->name('api_twitter_screen_name_exist');

$app->get('/api/message/get', function () use ($app) {
    \Longd\Controller\API::get_message($app);
})->name('api_get_message');

$app->run();

//---

function plog($obj){
    error_log(print_r($obj,1));
}