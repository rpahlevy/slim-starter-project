<?php

namespace App\Controllers;

class TestController
{
    public static function test($app, $req, $rsp, array $args=[])
    {
        return $app->view->render($rsp, 'template.phtml');
    }
}