<?php

namespace App\Git;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Repository
{
    public $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function lsTree($treeish, $path = null)
    {
        $command = ['git', 'ls-tree', $treeish];

        if ($path) {
            $command[] = $path;
        }

        $output = trim($this->run($command)->getOutput());

        $lines = explode("\n", $output);

        foreach ($lines as $line) {
            [, $type, $id, $path] = preg_split('/\s+/', $line);

            $items[$path] = new Obj($id, $type);
        }

        // TODO: Refactor, not sure if assoc array is a good thing here...
        uksort($items, function($a, $b) use ($items) {
            if ($items[$a]->type !== $items[$b]->type) {
                if ($items[$a]->type === 'tree') {
                    return -1;
                } else {
                    return 1;
                }
            }

            $aBasename = pathinfo($a)['basename'];
            $bBasename = pathinfo($b)['basename'];

            return strcmp($aBasename, $bBasename);
        });

        return $items;
    }

    protected function run(array $command): Process
    {
        $p = new Process($command);
        $p->setWorkingDirectory($this->path);
        $p->run();

        if (! $p->isSuccessful()) {
            throw new ProcessFailedException($p);   
        }

        return $p;
    }
}
