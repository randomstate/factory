<?php


namespace RandomState\Factory;


use Illuminate\Contracts\Validation\Factory;

interface Updater
{
    public function validateUpdateData(Factory $validator, array $data, $item);
    public function canUpdate($item, array $data);
    public function update($item, array $data);
}