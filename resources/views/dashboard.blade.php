<x-app-layout>
    <div class="container py-4">
        <h1 class="mb-4">Dashboard</h1>

        <div class="d-flex align-items-center mb-4">
            <h2 class="h3">Repositories</h2>
            <a class="btn btn-primary ms-auto" href="/new">Create new repo</a>
        </div>

        <ul>
            @foreach ($repositories as $repository)
                <li class="h4">
                    <a class="text-decoration-none" href="/{{ auth()->user()->username }}/{{ $repository->name }}">{{ auth()->user()->username }}/{{ $repository->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
