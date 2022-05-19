<?php

declare(strict_types=1);

namespace App\Traits;

trait CreateEntityTrait
{
    public function createEntity(string $class)
    {
        return new $class;
    }
}