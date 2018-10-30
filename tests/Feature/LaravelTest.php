<?php


namespace RandomState\Factory\Tests\Feature;


use RandomState\Factory\FactoryManager;
use RandomState\Factory\FactoryServiceProvider;
use RandomState\Factory\Tests\Stubs\Builder;
use RandomState\Factory\Tests\Stubs\Updater;
use RandomState\Factory\Tests\TestCase;

class LaravelTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->app['config']->set('factories', [
            'factories' => [
                FactoryManager::class => [
                    Builder::class,
                    Updater::class,
                    Builder::class,
                ]
            ]
        ]);

        $this->app->register(FactoryServiceProvider::class);
    }

    /**
     * @test
     */
    public function is_bound_into_ioc()
    {
        $manager = $this->app->make(FactoryManager::class);

        $this->assertCount(3, $factories = $manager->factories());

        $this->assertInstanceOf(Builder::class, $factories[0]);
        $this->assertInstanceOf(Updater::class, $factories[1]);
        $this->assertInstanceOf(Builder::class, $factories[2]);
    }
}