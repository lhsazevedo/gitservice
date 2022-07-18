<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class TreeController extends Controller
{
    public function __invoke(Request $request, $username, $repositoryName, $ref, $path)
    {
        $user = User::where('username', $username)->firstOrFail();

        $repository = Repository::query()
            ->where('user_id', $user->id)
            ->where('name', $repositoryName)
            ->firstOrFail();

        $repoFullName = $user->username . '/' . $repository->name;
        $repopath = storage_path() . '/app/repos/' . $repoFullName;

        // dd($repopath);
        chdir($repopath);
        
        $process = new Process(['git', 'ls-tree', $ref, $path . '/']);
        $process->run();

        $tree = trim($process->getOutput());
        // dd($tree);
        $lines = explode("\n", $tree);
        $items = [];

        foreach ($lines as $line) {
            [, $type, , $filePath] = preg_split('/\s+/', $line);

            $items[] = [
                'type' => $type,
                'path' => $filePath,
                'basename' => pathinfo($filePath)['basename']
            ];
        }

        usort($items, function($a, $b) {
            if ($a['type'] !== $b['type']) {
                if ($a['type'] === 'tree') {
                    return -1;
                } else {
                    return 1;
                }
            }

            return strcmp($a['basename'], $b['basename']);
        });

        
        return view('repository.tree', compact([
            'user',
            'repository',
            'ref',
            'path',
            'items',
        ]));
    }
}
