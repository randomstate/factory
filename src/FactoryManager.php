<?php


namespace RandomState\Factory;


use Illuminate\Contracts\Validation\Factory;
use RandomState\Factory\Exceptions\CannotFindBuilderFactoryForData;
use RandomState\Factory\Exceptions\CannotFindUpdaterFactoryForItemWithData;
use RandomState\Factory\Exceptions\SuppliedInvalidFactory;

class FactoryManager
{
    /**
     * @var array
     */
    protected $factories = [];

    /**
     * @var Factory
     */
    protected $validator;

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    public function register($factory)
    {
        $isFactory = ($factory instanceof Builder || $factory instanceof Updater);

        if($isFactory) {
            $this->factories[] = $factory;

            return $this;
        }

        throw new SuppliedInvalidFactory($factory);
    }

    public function factories()
    {
        return $this->factories;
    }

    public function canBuild(array $data)
    {
        foreach ($this->builders() as $factory) {
            if ($factory->canBuild($data)) {
                return $factory;
            }
        }

        return false;
    }

    public function canUpdate($item, array $data)
    {
        foreach ($this->updaters() as $factory) {
            if ($factory->canUpdate($item, $data)) {
                return $factory;
            }
        }

        return false;
    }

    public function build(array $data)
    {
        if ($factory = $this->canBuild($data)) {
            $factory->validateBuildData($this->validator, $data)->validate();

            return $factory->build($data);
        }

        throw new CannotFindBuilderFactoryForData($data);
    }

    public function update($item, array $data)
    {
        if ($factory = $this->canUpdate($item, $data)) {
            $factory->validateUpdateData($this->validator, $data, $item)->validate();

            return $factory->update($item, $data);
        }

        throw new CannotFindUpdaterFactoryForItemWithData($item, $data);
    }

    protected function builders()
    {
        return array_filter($this->factories, function($factory) {
           return $factory instanceof Builder;
        });
    }

    protected function updaters()
    {
        return array_filter($this->factories, function($factory) {
            return $factory instanceof Updater;
        });
    }
}