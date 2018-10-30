<?php


namespace RandomState\Factory\Tests\Feature;


use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use RandomState\Factory\Exceptions\CannotFindBuilderFactoryForData;
use RandomState\Factory\Exceptions\CannotFindUpdaterFactoryForItemWithData;
use RandomState\Factory\FactoryManager;
use RandomState\Factory\Tests\Stubs\Builder;
use RandomState\Factory\Tests\Stubs\Updater;
use RandomState\Factory\Tests\TestCase;
use Mockery as m;

class FactoryTest extends TestCase
{
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new FactoryManager(app(Factory::class));
    }

    /**
     * @test
     */
    public function can_register_factories_in_manager()
    {
        $this->manager->register(new Builder());

        $this->assertInstanceOf(Builder::class, $this->manager->factories()[0]);
        $this->assertCount(1, $this->manager->factories());
    }

    /**
     * @test
     */
    public function can_build_a_new_object_from_data()
    {
        $this->manager->register(new Builder(
            $mock = m::mock()
        ));

        $this->assertEquals($mock, $this->manager->build(['some test data']));
    }

    /**
     * @test
     */
    public function can_update_an_object_from_data()
    {
        $this->manager->register(new Updater());


        $mock = m::mock();
        $this->assertEquals($mock, $this->manager->update($mock, ['some test data']));
    }

    /**
     * @test
     */
    public function cannot_build_objects_where_no_build_factory_is_available()
    {
        $this->expectException(CannotFindBuilderFactoryForData::class);

        $this->manager->build(['type' => 'test']);
    }

    /**
     * @test
     */
    public function cannot_update_objects_where_no_update_factory_is_available()
    {
        $mock = m::mock();

        $this->expectException(CannotFindUpdaterFactoryForItemWithData::class);

        $this->manager->update($mock, ['type' => 'test']);
    }

    /**
     * @test
     */
    public function checks_validation_when_building()
    {
        // create a factory that builds anything but always fails validation
        // expect validation exception
        $factory = new class implements \RandomState\Factory\Builder
        {
            public function validateBuildData(Factory $validator, array $data)
            {
                return $validator->make($data, [
                    'doesnt_exist' => 'required',
                ]);
            }

            public function canBuild(array $data)
            {
                return true;
            }

            public function build(array $data)
            {

            }
        };

        $this->manager->register($factory);

        $this->expectException(ValidationException::class);

        $this->manager->build(['test' => 'test']);
    }

    /**
     * @test
     */
    public function checks_validation_when_updating()
    {
        // create a factory that updates anything but always fails validation
        // expect validation exception
        $factory = new class implements \RandomState\Factory\Updater
        {
            public function validateUpdateData(Factory $validator, array $data, $item)
            {
                return $validator->make($data, [
                    'doesnt_exist' => 'required',
                ]);
            }

            public function canUpdate($item, array $data)
            {
                return true;
            }

            public function update($item, array $data)
            {

            }
        };

        $this->manager->register($factory);
        $mock = m::mock();

        $this->expectException(ValidationException::class);

        $this->manager->update($mock, ['test' => 'test']);
    }
}