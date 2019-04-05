<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('[/]', function($req, $rsp) {
    return $this->view->render($rsp, 'template.phtml');
});
