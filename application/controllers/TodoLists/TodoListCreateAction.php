<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controllers\TodoLists;

use Application\Controllers\BaseAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Application\Models\TodoList;

/**
 * Description of TodoListCreateAction
 *
 * @author russel
 */
class TodoListCreateAction extends BaseAction {

    public function __invoke(Request $req, Response $resp) {
        $postData = $req->getParsedBody();
        
        if (isset($postData['name'])) {
            $name = trim($postData['name']);
            $todo = new TodoList();
            $todo->setName($name)->save();
        }

        return $resp->withRedirect($this->router->pathFor('index'));
    }

}
