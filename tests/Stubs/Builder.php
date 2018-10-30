<?php


namespace RandomState\Factory\Tests\Stubs;


use Illuminate\Contracts\Validation\Factory;

class Builder implements \RandomState\Factory\Builder
{
    protected $mock;

    public function __construct($mock = null)
    {
        $this->mock = $mock;
    }

    public function validateBuildData(Factory $validator, array $data)
    {
        return $validator->make($data, []);
    }

    public function build(array $data)
    {
        return $this->mock;
    }

    public function canBuild(array $data)
    {
        return true;
    }

}