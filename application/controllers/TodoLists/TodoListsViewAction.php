<?php

namespace Application\Controllers\TodoLists;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Application\Models\TodoList;
use Application\Controllers\BaseAction;
/**
 * Description of TodoListsViewAction
 *
 * @author russel
 */
class TodoListsViewAction extends BaseAction {
        
    public function __invoke(Request $req,Response $resp) {
         if (!isset($_SESSION['todos'])) {
            $_SESSION['todos'] = [];
        }
        
        $todos = [];

        foreach ($_SESSION['todos'] as $key => $value) {
            $todos[] = new TodoList($key);
        }

        $viewData = [
            "router" => $this->router,
            'todos' => $todos
        ];

        return $this->view->render($resp, 'index.phtml', $viewData);
    }
}
