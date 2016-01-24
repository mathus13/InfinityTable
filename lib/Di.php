<?php

namespace Infinity;

use Pimple\Container;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

class Di
{
    private static $di;

    public static function getDi()
    {
        if (!self::$di) {
            self::setDi();
        }
        return self::$di;
    }

    private static function setDi()
    {
        $di = new Container;

        //hook system
        $di['hooks'] = function ($c) {
            $hooks = $c['config']->get('hooks');
            $hooks = ($hooks)? (array) $hooks : array();
            return new \Ethereal\Hooks($hooks);
        };

        $di['logger'] = function ($c) {
            $log = new \Monolog\Logger('apache');
            $log->pushHandler(new \Monolog\Handler\ErrorLogHandler);
            return $log;
        };

        //database
        $di['db'] = function ($c) {
            return new \Ethereal\Db($c['config']);
        };

        //config system
        $di['config'] = function ($c) {
            $cache = $c['cache'];
            $config =  new AppConfig($cache);
            $config->setLogger($c['logger']);
            return $config;
        };

        //Link service
        $di['links'] = function ($c) {
            $table = new Links\Table($c['db']);
            return new Links($table);
        };

        //caching layer
        $di['cache'] = function ($c) {
            $cache =  new \Ethereal\Cache([
                'scheme' => 'tcp',
                'host'   => 'redis',
                'port'   => 6379,
            ]);
            $cache->setNamespace('bpe-clients');
            return $cache;
        };
        self::$di = $di;
    }
}
