<?php


namespace RandomState\Factory\Tests\Stubs;


use Illuminate\Contracts\Validation\Factory;

class Updater implements \RandomState\Factory\Updater
{
    public function validateUpdateData(Factory $validator, array $data, $item)
    {
        return $validator->make($data, []);
    }

    public function update($item, array $data)
    {
        return $item;
    }

    public function canUpdate($item, array $data)
    {
        return true;
    }

}