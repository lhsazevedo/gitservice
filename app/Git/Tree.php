<?php

namespace App\Git;

use Illuminate\Support\Collection;

class Tree extends Obj
{
    // TODO: Dereference
    // public string $treeish;

    public string $type = 'tree';

    public Collection $children;

    public function __construct(Repository $repository, $treeish)
    {
        $this->repository = $repository;
        // TODO: Deference?
        $this->ref = $treeish;
        $this->children = new Collection();
    }

    public function sort()
    {
        $this->children = $this->children
            ->sortBy([
                function($a, $b) {
                    if ($a->obj->type === $b->obj->type) {
                        return 0;
                    }

                    return $a->obj instanceof Tree ? -1 : 1;
                },
                [ 'path', 'asc' ]
            ]);
    }

    public function latestCommitMessage($ref, $path) {
        return trim($this->repository->run([
            'git',
            'log',
            '--max-count=1',
            '--format=%s',
            $ref,
            '--',
            $path,
        ])->getOutput());
    }
}
