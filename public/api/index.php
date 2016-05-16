<?php

if (class_exists('Redis')) {
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', "redis:6379");
}
session_start();

// Get the start time and memory for use later

define('REQTIME', time());
defined('APP_START_TIME') or define('APP_START_TIME', microtime(true));
defined('APP_START_MEM') or define('APP_START_MEM', memory_get_usage());

define('DOCROOT', dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR);
require_once DOCROOT.'vendor/autoload.php';
use Infinity\Di;
use Slim\App;

error_log('loaded');
//Set Container
$di = Di::getDi();

//Load Slim
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$container = new \Slim\Container($configuration);
$app = new \Slim\App($container);

//api resources, key to controller
$resources = array(
//Clients
    'sessions' => array(
        'class' => 'Infinity\Games\Controllers\Games',
        'collectionActions' => array(
            'GET'     => 'listItems',
            'POST'    => 'createItem',
            'OPTIONS' => 'getOptions'
        ),
        'itemActions' => array(
            'GET'     => 'listItems',
            'PUT'     => 'updateItem',
            'DELETE'  => 'delete',
            'OPTIONS' => 'getItemOptions'
        )
    ),
    'groups' => array(
        'class' => 'Infinity\Groups\Controllers\Groups',
        'collectionActions' => array(
            'GET'     => 'listItems',
            'POST'    => 'createItem',
            'OPTIONS' => 'getOptions'
        ),
        'itemActions' => array(
            'GET'     => 'listItems',
            'PUT'     => 'updateItem',
            'DELETE'  => 'delete',
            'OPTIONS' => 'getItemOptions'
        )
    ),
    'campaigns' => array(
        'class' => 'Infinity\Campaigns\Controllers\Campaign',
        'collectionActions' => array(
            'GET'     => 'listItems',
            'POST'    => 'createItem',
            'OPTIONS' => 'getOptions'
        ),
        'itemActions' => array(
            'GET'     => 'listItems',
            'PUT'     => 'updateItem',
            'DELETE'  => 'delete',
            'OPTIONS' => 'getItemOptions'
        )
    ),
);

//build resources
foreach ($resources as $resource => $config) {
    $app->group("/{$resource}", function () use ($di, $config) {
        $this->map(['GET', 'POST', 'OPTIONS'], '', function ($request, $response, $args) use ($di, $config) {
            $actions = $config['collectionActions'];
            $args = array_merge($request->getQueryParams(), $args);
            
            $controller = new $config['class'](
                $request,
                $response,
                $di,
                $args
            );
            return $controller->fire($actions[$request->getMethod()]);
        });
        $this->map(['GET', 'PUT', 'DELETE', 'OPTIONS'], '/{id}', function ($request, $response, $args) use ($di, $config) {
            $actions = $config['itemActions'];
            $controller = new $config['class'](
                $request,
                $response,
                $di,
                $args
            );
            $resp = $controller->fire($actions[$request->getMethod()]);
            $di['logger']->addInfo("args", array($actions[$request->getMethod()], $args));
            $time = time() - REQTIME;
            error_log("{$class}->{$action} in {$time}() seconds");
            return $resp;
        });
    });
}

$app->run();
