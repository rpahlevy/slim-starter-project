<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// not found handler
$container['notFoundHandler'] = function($c) {
    return function ($req, $rsp) use ($c) {
        return $c->view->render($rsp->withStatus(404), '404.phtml');
    };
};

// error handler
if (!$container->get('settings')['debugMode'])
{
    $container['errorHandler'] = function($c) {
        return function ($req, $rsp) use ($c) {
            return $c->view->render($rsp->withStatus(500), '500.phtml');
        };
    };
    $container['phpErrorHandler'] = function ($c) {
        return $c['errorHandler'];
    };
}

// monolog
$container['log'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    //$logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// db
$container['db'] = function($c) {
    $settings = $c->get('settings')['db'];
    return new \Medoo\Medoo($settings['medoo']);
};

// session helper
$container['session'] = function($c) {
    return \App\Vars\Session::getInstance();
};
