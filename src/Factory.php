<?php


namespace RandomState\Factory;


abstract class Factory implements Builder, Updater
{
    public function canUpdate($item, array $data)
    {
        return $this->canBuild($data);
    }
}