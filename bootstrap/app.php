<?php

use DI\Bridge\Slim\Bridge as SlimAppFactory;
use DI\Container;
use App\Providers\ServiceProvider;
use App\Http\Middleware\RequestLoggingMiddleware;

// load composer autoload
require __DIR__ . "/../vendor/autoload.php";

// create di container
$container = new Container;

// load app settings
$settings = require __DIR__ . "/../app/settings.php";
$settings($container);

// create slim app with container
$app = SlimAppFactory::create($container);

// parse json/body requests
$app->addBodyParsingMiddleware();

// add request logging middleware
$app->add(RequestLoggingMiddleware::class);

// load routes
$routes = require __DIR__ . "/../app/routes.php";
$routes($app);

// setup service providers
ServiceProvider::setup($app, config('app.providers'));

// run app
$app->run();
