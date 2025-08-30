<?php

use DI\Bridge\Slim\Bridge as SlimAppFactory;
use DI\Container;
use App\Providers\ServiceProvider;
use App\Http\Middleware\RequestLoggingMiddleware;

require __DIR__ . "/../vendor/autoload.php";

$container = new Container;
$settings = require __DIR__ . "/../app/settings.php";
$settings($container);

$app = SlimAppFactory::create($container);
$app->addBodyParsingMiddleware();
$app->add(RequestLoggingMiddleware::class);


$routes = require __DIR__ . "/../app/routes.php";
$routes($app);

ServiceProvider::setup($app, config('app.providers'));

$app->run();
