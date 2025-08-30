<?php

namespace App\Providers;

use Illuminate\Database\Capsule\Manager as Capsule;

class DBServiceProvider extends ServiceProvider
{
    public function register()
    {
        $container = $this->app->getContainer();
        $container->set(Capsule::class, function () {
            $capsule = new Capsule;
            $capsule->addConnection(config('database'));
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
            return $capsule;
        });
    }

    public function boot()
    {
        $container = $this->app->getContainer();
        $container->get(Capsule::class);
    }
}