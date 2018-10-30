<?php


namespace RandomState\Factory;


use Illuminate\Support\ServiceProvider;

class FactoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/factories.php' => config_path('factories.php'),
        ]);
    }

    public function register()
    {
        // loop through config and bind factory managers with their own sub-factories.
        // publish config file upon request

        $this->mergeConfigFrom(
            __DIR__ . '/../config/factories.php', 'factories'
        );

        foreach(config('factories.factories') as $manager => $factories) {
            $this->app->afterResolving($manager, function(FactoryManager $manager) use($factories) {
                foreach($factories as $factory) {
                    $manager->register($this->app->make($factory));
                }
            });
        }
    }
}