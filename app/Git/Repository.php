<?php

namespace App\Git;

use Exception;
use stdClass;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Repository
{
    public $path;

    public function __construct(string $path)
    {
        // TODO: Check if it is a real git repository

        $this->path = $path;
    }

    public function listTree2($treeish, $path = null): Tree
    {
        // TODO: This will fail if object name doesn't exists.
        // Maybe that's why other libs force you to list refs and pick one up?
        // Well, I guess we could just check before running (or after? lol)
        $command = ['git', 'ls-tree', $treeish];

        if ($path) {
            $command[] = $path;
        }

        $output = trim($this->run($command)->getOutput());

        $lines = explode("\n", $output);

        $tree = new Tree($this, $treeish);

        foreach ($lines as $line) {
            [, $type, $id, $path] = preg_split('/\s+/', $line);

            if ($type === 'tree') {
                $item = new Tree($this, $id);
            } else {
                $item = new Obj($this, $id, $type);
            }

            $entry = new TreeEntry($this, $path, $item);
            // $entry = new stdClass();
            // $entry->path = $path;
            // $entry->obj = $item;

            $tree->children->put($path, $entry);
        }

        $tree->sort();

        return $tree;
    }

    public function listTree($treeish, $path = null)
    {
        // TODO: This will fail if object name doesn't exists.
        // Maybe that's why other libs force you to list refs and pick one up?
        // Well, I guess we could just check before running (or after? lol)
        $command = ['git', 'ls-tree', $treeish];

        if ($path) {
            $command[] = $path;
        }

        $output = trim($this->run($command)->getOutput());

        $lines = explode("\n", $output);

        foreach ($lines as $line) {
            [, $type, $id, $path] = preg_split('/\s+/', $line);

            $items[$path] = new Obj($this, $id, $type);
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

    public function isEmpty(): bool
    {
        try {
            $this->run(['git', 'show-ref', '--head', '^HEAD$']);
        } catch (ProcessFailedException $e) {
            return true;
        }

        return false;
    }

    public function run(array $command): Process
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
