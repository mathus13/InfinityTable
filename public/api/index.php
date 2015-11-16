<?php

if (class_exists('Redis')) {
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', "redis:6379");
}
session_start();

// Get the start time and memory for use later
defined('APP_START_TIME') or define('APP_START_TIME', microtime(true));
defined('APP_START_MEM') or define('APP_START_MEM', memory_get_usage());

define('DOCROOT', __DIR__.DIRECTORY_SEPARATOR);
require_once dirname(__DIR__).'/vendor/autoload.php';
use Ethereal\Cache;
use Ethereal\Config;
use Ethereal\Db;
use Slim\App;
use Clients\Company\Controllers\Clients as ClientController;

/**
 * Set error reporting and display errors settings.  You will want to change these when in production.
 */
error_reporting(-1);
ini_set('display_errors', 1);

//Set Container
$container = new \Slim\Container;

//hook system
$container['loaded_hooks'] = (isset($hooks))? $hooks : array();
$container['hooks'] = function ($c) {
    return new Ethereal\Hooks($c['loaded_hooks']);
};

//database
$container['db'] = function ($c) {
    return new Ethereal\Db($c['config']);
};

//config system
$container['config'] = function ($c) {
    $cache = $c['cache'];
    return new Ethereal\Config($cache);
};

//caching layer
$container['cache'] = function ($c) {
    $cache =  new Ethereal\Cache([
        'scheme' => 'tcp',
        'host'   => 'redis',
        'port'   => 6379,
    ]);
    $cache->setNamespace('infinityTable');
    return $cache;
};

//Load Slim
$app = new \Slim\App($container);

$app->group('/groups', function () {
    $this->map(['GET', 'POST', 'OPTIONS'], '', function ($request, $response, $args) {
        $actions = array(
            'GET'     => 'listClients',
            'POST'    => 'create',
            'OPTIONS' => 'getOptions'
        );
        $args = array_merge($request->getQueryParams(), $args);
        
        $controller = new GroupController(
            $request,
            $response,
            $this->getContainer(),
            $args
        );
        return $controller->fire($actions[$request->getMethod()]);
    });
    $this->map(['GET', 'PUT', 'DELETE', 'OPTIONS'], '/{client_id}', function ($request, $response, $args) {
        $actions = array(
            'GET'     => 'getById',
            'PUT'     => 'update',
            'DELETE'  => 'delete',
            'OPTIONS' => 'getItemOptions'
        );
        $controller = new GroupController(
            $request,
            $response,
            $this->getContainer(),
            $args
        );
        return $controller->fire($actions[$request->getMethod()]);
    });
});
