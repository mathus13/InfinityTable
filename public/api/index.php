<?php

if (class_exists('Redis')) {
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', "redis:6379");
}
session_start();

// Get the start time and memory for use later
defined('APP_START_TIME') or define('APP_START_TIME', microtime(true));
defined('APP_START_MEM') or define('APP_START_MEM', memory_get_usage());

define('DOCROOT', dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR);
require_once DOCROOT.'/vendor/autoload.php';
use Ethereal\Cache;
use Ethereal\Config;
use Ethereal\Db;
use Infinity\AppConfig;
use Infinity\Campaigns\Controllers\Campaign as CampaignController;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Pimple\Container as Di;
use Slim\App;

//Set Container
$di = new Di;

//hook system
$di['loaded_hooks'] = (isset($hooks))? $hooks : array();
$di['hooks'] = function ($c) {
    return new Ethereal\Hooks($c['loaded_hooks']);
};

$di['logger'] = function ($c) {
    $log = new Monolog\Logger('apache');
    $log->pushHandler(new Monolog\Handler\ErrorLogHandler);
    return $log;
};

//database
$di['db'] = function ($c) {
    return new Ethereal\Db($c['config']);
};

//config system
$di['config'] = function ($c) {
    $cache = $c['cache'];
    $config =  new AppConfig($cache, $c['hooks']);
    $c['logger']->addInfo('Db', (array) $config->get('db'));
    return $config;
};

//caching layer
$di['cache'] = function ($c) {
    $cache =  new Ethereal\Cache([
        'scheme' => 'tcp',
        'host'   => 'redis',
        'port'   => 6379,
    ]);
    $cache->setNamespace('infinityTable');
    return $cache;
};

//Load Slim
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$container = new \Slim\Container($configuration);
$app = new \Slim\App($container);
$app->group('/groups', function () use ($di) {
    $this->map(['GET', 'POST', 'OPTIONS'], '', function ($request, $response, $args) use ($di) {
        $actions = array(
            'GET'     => 'listItems',
            'POST'    => 'create',
            'OPTIONS' => 'getOptions'
        );
        $args = array_merge($request->getQueryParams(), $args);
        
        $controller = new GroupController(
            $request,
            $response,
            $di,
            $args
        );
        return $controller->fire($actions[$request->getMethod()]);
    });
    $this->map(['GET', 'PUT', 'DELETE', 'OPTIONS'], '/{id}', function ($request, $response, $args) use ($di) {
        $actions = array(
            'GET'     => 'getById',
            'PUT'     => 'update',
            'DELETE'  => 'delete',
            'OPTIONS' => 'getItemOptions'
        );
        $controller = new GroupController(
            $request,
            $response,
            $di,
            $args
        );
        return $controller->fire($actions[$request->getMethod()]);
    });
});

$app->group('/campaigns', function () use ($di) {
    $this->map(['GET', 'POST', 'OPTIONS'], '', function ($request, $response, $args) use ($di) {
        $actions = array(
            'GET'     => 'listItems',
            'POST'    => 'create',
            'OPTIONS' => 'getOptions'
        );
        $args = array_merge($request->getQueryParams(), $args);
        
        $controller = new CampaignController(
            $request,
            $response,
            $di,
            $args
        );
        $di['logger']->addInfo($actions[$request->getMethod()]);
        return $controller->fire($actions[$request->getMethod()]);
    });
    $this->map(['GET', 'PUT', 'DELETE', 'OPTIONS'], '/{id}', function ($request, $response, $args) use ($di) {
        $actions = array(
            'GET'     => 'getById',
            'PUT'     => 'update',
            'DELETE'  => 'delete',
            'OPTIONS' => 'getItemOptions'
        );
        $controller = new CampaignController(
            $request,
            $response,
            $di,
            $args
        );
        return $controller->fire($actions[$request->getMethod()]);
    });
});

$app->group('/games', function () use ($di) {
    $this->map(['GET', 'POST', 'OPTIONS'], '', function ($request, $response, $args) use ($di) {
        $actions = array(
            'GET'     => 'listItems',
            'POST'    => 'create',
            'OPTIONS' => 'getOptions'
        );
        $args = array_merge($request->getQueryParams(), $args);
        
        $controller = new CampaignController(
            $request,
            $response,
            $di,
            $args
        );
        $di['logger']->addInfo($actions[$request->getMethod()]);
        return $controller->fire($actions[$request->getMethod()]);
    });
    $this->map(['GET', 'PUT', 'DELETE', 'OPTIONS'], '/{id}', function ($request, $response, $args) use ($di) {
        $actions = array(
            'GET'     => 'getById',
            'PUT'     => 'update',
            'DELETE'  => 'delete',
            'OPTIONS' => 'getItemOptions'
        );
        $controller = new CampaignController(
            $request,
            $response,
            $di,
            $args
        );
        return $controller->fire($actions[$request->getMethod()]);
    });
});

$app->run();
