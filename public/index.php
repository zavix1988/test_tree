<?php

use Core\Router;

require __DIR__.'/../vendor/autoload.php';
require '../config/config_const.php';

$query = trim($_SERVER["REQUEST_URI"], '/');


Router::add('^api/tree-node/add$', ['prefix' => 'Api', 'controller' => 'TreeNode', 'action' => 'add']);
Router::add('^api/tree-node/edit/(?P<id>[0-9]+)$', ['prefix' => 'Api', 'controller' => 'TreeNode', 'action' => 'edit']);
Router::add('^api/tree-node/delete/(?P<id>[0-9]+)$', ['prefix' => 'Api', 'controller' => 'TreeNode', 'action' => 'delete']);

Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
Router::add('^api/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', ['prefix' => 'Api']);
try {
    Router::dispatch($query);
} catch (Exception $e) {
    dd($e->getMessage());
}