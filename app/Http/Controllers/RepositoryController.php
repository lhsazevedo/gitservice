<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class RepositoryController extends Controller
{
    public function create()
    {
        return view('repository.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'alpha_dash',
        ]);

        $user = auth()->user();

        $repo = Repository::create([
            'user_id' => $user->id,
            'name' => $request->name,
        ]);

        mkdir(storage_path() . "/app/repos/{$user->username}/{$repo->name}", 0777, true);

        chdir(storage_path() . "/app/repos/{$user->username}/{$repo->name}");

        `git init --bare --shared`;

        return redirect("/repositories/{$user->username}/{$repo->name}");
    }

    public function show(Request $request, $username, $repositoryName)
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

        $process = new Process(['git', 'ls-tree', 'main']);
        $process->run();

        $tree = trim($process->getOutput());

        if ($tree === "") {
            return "Repositório vazio";
        }

        $lines = explode("\n", $tree);
        $items = [];

        foreach ($lines as $line) {
            [$mode, $type, $id, $path] = preg_split('/\s+/', $line);
            $items[] = [
                'type' => $type,
                'path' => $path,
                'basename' => pathinfo($path)['basename'],
            ];
        }

        //dd($items);

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

        return view('repository.show', compact([
            'user',
            'repository',
            'items',
        ]));
    }
}