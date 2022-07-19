<?php

namespace App\Git;

class Obj
{
    public string $objectish;

    public string $type;

    public function __construct($objectish, $type)
    {
        $this->objectish = $objectish;
        $this->type = $type;
    }
}
