<?php

namespace App\Providers;

use App\Support\View;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class MonoLogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->getContainer()->set(LoggerInterface::class, function () {
            $logger = new Logger('requests');
            $logger->pushHandler(
                new StreamHandler(config('logger.path'), Logger::INFO)
            );

            return $logger;
        });
    }

    public function boot() {}
}
