<?php

namespace src\Domain\Repository;

interface RepositoryInterface
{
    public function getById($id);
    public function getAll();
    public function persist($entity);
}
