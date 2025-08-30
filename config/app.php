<?php

return [
    'providers' => [
        \App\Providers\EnvironmentVariablesServiceProvider::class,
        \App\Providers\ErrorMiddlewareServiceProvider::class,
        \App\Providers\DBServiceProvider::class,
        \App\Providers\MonoLogServiceProvider::class,
    ]
];
