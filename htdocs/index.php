<?php
require_once('../vendor/autoload.php');
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/../class');
spl_autoload_register(function($class) {
    $parts = explode('\\', $class);

    # Support for non-namespaced classes.
    $parts[] = str_replace('_', DIRECTORY_SEPARATOR, array_pop($parts));

    $path = implode(DIRECTORY_SEPARATOR, $parts);
echo $path;
    $file = stream_resolve_include_path($path.'.php');
    echo $file;
    if($file !== false) {
        require $file;
    }
});

$app = new \Slim\Slim();
//$user = new \Longd\Model\User();
$user = new \Uzulla\CFEDb2();

$app->get('/', function () use ($app) {
    echo "longd";
});


$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->run();

