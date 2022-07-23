<?php

namespace App\Git;

class Obj
{
    public Repository $repository;

    public string $ref;

    public string $type;

    public function __construct(Repository $repository, $ref, $type)
    {
        $this->repository = $repository;
        $this->ref = $ref;
        $this->type = $type;
    }
}
