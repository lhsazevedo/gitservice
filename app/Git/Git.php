<?php

namespace App\Git;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Git
{
    public static function init(string $path)
    {
        $p = new Process(['git', 'init', '--bare']);
        $p->setWorkingDirectory($path);
        $p->run();

        if (! $p->isSuccessful()) {
            throw new ProcessFailedException($p);   
        }

        return new Repository($path);
    }
}
