<?php

require "vendor/autoload.php";

use Slim\App;
use Slim\Views\PhpRenderer as View;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Application\Models\TodoList;
use Slim\Exception\NotFoundException;

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

$app->get("/reset",function(Request $req,Response $resp) use($container){
    session_destroy();
    return $resp->withRedirect("/");
});

$app->get("/",function(Request $req,Response $resp) use($container){
    /*@var View */
    if(!isset($_SESSION['todos'])){
        $_SESSION['todos']=[];
    }
    $view = $container['view'];
    $router = $container['router'];
    $todos = [];
    
    foreach ($_SESSION['todos'] as $key => $value) {
        $todos[] = new TodoList($key);
    }

    $viewData = [
        "router" =>$router, 
        'todos'=>$todos
    ];

    return $view->render($resp,'index.phtml',$viewData);
})->setName('index');

$app->get("/todos/{todoId:[0-9]+}",function(Request $req,Response $resp, $args) use($container){
    $router = $container['router'];    
    $id = (int) $args["todoId"];
    
    if($id < 0 || $id >= count($_SESSION['todos'])){
        throw new NotFoundException($req,$resp);
    }
    
    $view = $container['view'];
    $todo = new TodoList($id);

    $viewData = [
        "router" =>$router, 
        'todo'=>$todo
    ];

    return $view->render($resp,'todo.phtml',$viewData);
    
})->setName('view-todo');

$app->post("/todos/{todoId:[0-9]+}/add",function(Request $req,Response $resp, $args) use($container){
    
    $id = (int) $args["todoId"];
    $router = $container['router'];

    
    if($id < 0 || $id >= count($_SESSION['todos'])){
        throw new NotFoundException($req,$resp);
    }

    $postData = $req->getParsedBody();

    if(isset($postData['name'])){
        $name = trim($postData['name']);
        $todo = new TodoList($id);
        $todo->add($todo->size(),$name)->save();
    
    }    
    return $resp->withRedirect($router->pathFor('view-todo',['todoId'=>$id]));

})->setName('add-todo');

$app->map(['POST','DELETE'],"/todos/{todoId:[0-9]+}/remove",function(Request $req,Response $resp, $args) use($container){
    
    $id = (int) $args["todoId"];
    $router = $container['router'];

    
    if($id < 0 || $id >= count($_SESSION['todos'])){
        throw new NotFoundException($req,$resp);
    }

    $postData = $req->getParsedBody();

    if(isset($postData['id'])){
        $i = (int) $postData['id'];
        $todo = new TodoList($id);
        $todo->remove($i)->save();
    }    
    return $resp->withRedirect($router->pathFor('view-todo',['todoId'=>$id]));

})->setName('remove-todo');

$app->post("/create",function(Request $req,Response $resp, $args) use($container){
    $postData = $req->getParsedBody();
    $router = $container['router'];

    if(isset($postData['name'])){
        $name = trim($postData['name']);
        $todo = new TodoList();
        $todo->setName($name)->save();
    }

    return $resp->withRedirect($router->pathFor('index'));

})->setName('create-todo');

session_start();

$app->run();