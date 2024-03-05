<?php
require_once 'router.php';

$router = new Router();

// Esegui il router
echo $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

