<?php


namespace RandomState\Factory;


use Illuminate\Contracts\Validation\Factory;

interface Builder
{
    public function validateBuildData(Factory $validator, array $data);
    public function canBuild(array $data);
    public function build(array $data);
}