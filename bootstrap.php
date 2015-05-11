<?php

require_once 'vendor\autoload.php';

$app = new Code\Sistema\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/src/Code/Sistema/views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
