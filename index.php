<?php

require "vendor/autoload.php";

use Slim\App;
use Slim\Views\PhpRenderer as View;
use  Psr\Http\Message\ServerRequestInterface as Request;
use  Psr\Http\Message\ResponseInterface as Response;

/* @var App $app */

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

$app = new App($config);

$container = $app->getContainer();

$container['view'] = function(){
    return new View('application/views');
};

$app->get("/",function(Request $req,Response $resp) use($container){
    /*@var View */
    $view = $container['view'];
    return $view->render($resp,'index.phtml');
});

$app->run();