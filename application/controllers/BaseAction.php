<?php

namespace Application\Controllers;

use Psr\Container\ContainerInterface as Container;

/**
 * Description of BaseAction
 *
 * @author russel
 */
abstract class BaseAction {
    protected $view;
    protected $router;

    public function __construct(Container $container) {
        $this->view = $container['view'];
        $this->router = $container['router'];
    }
}
