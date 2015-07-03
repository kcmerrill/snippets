<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/models/snippet.php';

use League\Monga;

$app = new Silex\Application();

$app['debug'] = true;

$app['theme'] = 'bluewhite';

$app['log'] = function ($c) use ($app) {
    $log = new \kcmerrill\utility\snitchin(50,'file');
    $log['default']->snitcher('file', dirname(__DIR__) . '/logs/snitchin_' . date('dmY') . '.log');
    return $log;
};

$app['db'] = function ($c) use ($app) {
    $connection = new \MongoClient('mongodb://172.17.42.1');
    return $connection->snippets->snippets;
};

$app['snippets'] = function($c) use ($app) {
    return new snippet($app['db'], $app['log']);
};

/* Includes */
include __DIR__ . '/routes.php';
