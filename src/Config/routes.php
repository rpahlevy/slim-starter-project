<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('[/]', function($req, $rsp, array $args) {
    return \App\Controllers\TestController::test($this, $req, $rsp, $args);
});
