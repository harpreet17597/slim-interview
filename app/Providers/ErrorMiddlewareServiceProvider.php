<?php

namespace App\Providers;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

class ErrorMiddlewareServiceProvider extends ServiceProvider {

    public function register() {
      
      // $this->app->addErrorMiddleware(
      //   config('middleware.error_details.displayErrorDetails'),
      //   config('middleware.error_details.logErrors'),
      //   config('middleware.error_details.logErrorDetails')
      // );

      if(env('APP_DEBUG'))
      $this->app->add(new WhoopsMiddleware());
    }

    public function boot() {

    }
}