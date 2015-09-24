<?php

if(class_exists('Redis')){
	ini_set('session.save_handler','redis');
	ini_set('session.save_path', "redis:6379");
}
session_start();

// Get the start time and memory for use later
defined('APP_START_TIME') or define('APP_START_TIME', microtime(true));
defined('APP_START_MEM') or define('APP_START_MEM', memory_get_usage());

define('DOCROOT', __DIR__.DIRECTORY_SEPARATOR);
require_once dirname(__DIR__).'/vendor/autoload.php';

/**
 * Set error reporting and display errors settings.  You will want to change these when in production.
 */
error_reporting(-1);
ini_set('display_errors', 1);

//Load Slim
$app = new \Slim\Slim();

//Get Cache Client
$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => 'redis',
    'port'   => 6379,
]);

