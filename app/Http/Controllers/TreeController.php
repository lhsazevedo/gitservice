<?php

namespace App\Http\Controllers;

use App\Git\Repository as GitRepository;
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

        $gitRepo = new GitRepository($repopath);

        $tree = $gitRepo->listTree2($ref, $path . '/');
        $tree->sort();
        $items = $tree->children;
        
        return view('repository.tree', compact([
            'user',
            'repository',
            'ref',
            'path',
            'items',
        ]));
    }
}
