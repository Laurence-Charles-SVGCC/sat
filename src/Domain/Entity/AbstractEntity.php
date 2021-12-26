<?php

namespace src\Domain\Entity;

abstract class AbstractEntity
{
    protected $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): AbstractEntity
    {
        $this->id = $id;
        return $this;
    }
}
