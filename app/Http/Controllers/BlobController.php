<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class BlobController extends Controller
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

        $process = new Process(['git', 'cat-file', 'blob', "$ref:$path"]);
        $process->run();

        $content = trim($process->getOutput());
        
        return view('repository.blob', compact([
            'user',
            'repository',
            'ref',
            'path',
            'content'
        ]));
    }
}
