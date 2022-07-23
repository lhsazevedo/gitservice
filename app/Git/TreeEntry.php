<?php

namespace App\Git;

class TreeEntry
{
    public function __construct(
        protected Repository $repository,
        public string $path,
        public Obj $obj
    )
    {
    }

    public function latestCommitMessage($ref)
    {
        return trim($this->repository->run([
            'git',
            'log',
            '--max-count=1',
            '--format=%s',
            $ref,
            '--',
            $this->path,
        ])->getOutput());
    }
}
