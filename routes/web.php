<?php

use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\TreeController;
use App\Http\Controllers\BlobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'repositories' => auth()->user()->repositories
    ]);
})->middleware(['auth'])->name('dashboard');

Route::get('/new', [RepositoryController::class, 'create']);
Route::POST('/new', [RepositoryController::class, 'store']);

Route::get('/{user}/{repository}', [RepositoryController::class, 'show']);
Route::get(
    '/{user}/{repository}/tree/{ref?}/{path?}',
    TreeController::class
)->where('path', '.*');

Route::get(
    '/{user}/{repository}/blob/{ref?}/{path?}',
    BlobController::class
)->where('path', '.*');

require __DIR__.'/auth.php';
