<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/models/snippet.php';

use League\Monga;

$app = new Silex\Application();

$app['debug'] = true;

$app['theme'] = 'bluewhite';

$app['db'] = function($c) use ($app) {
    return new snippet(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'snippets');
};

/* Includes */
include __DIR__ . '/routes.php';
