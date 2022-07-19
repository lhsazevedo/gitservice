<?php

namespace App\Http\Controllers;

use App\Git\Git;
use App\Git\Repository as GitRepository;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
            'default_branch' => 'master',
        ]);

        $path = storage_path() . "/app/repos/{$user->username}/{$repo->name}";

        File::makeDirectory($path, 0777, true);

        Git::init($path);

        return redirect("/{$user->username}/{$repo->name}");
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

        $gitRepo = new GitRepository($repopath);

        $items = $gitRepo->lsTree($repository->default_branch);

        return view('repository.show', compact([
            'user',
            'repository',
            'items',
        ]));
    }

    public function destroy(Request $request, $username, $repositoryName)
    {
        $user = User::where('username', $username)->firstOrFail();

        $repository = Repository::query()
            ->where('user_id', $user->id)
            ->where('name', $repositoryName)
            ->firstOrFail();

        $repoFullName = $user->username . '/' . $repository->name;
        $repopath = storage_path() . '/app/repos/' . $repoFullName;
    
        File::deleteDirectory($repopath);

        $repository->delete();

        return redirect("/dashboard");
    }
}