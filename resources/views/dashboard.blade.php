<x-app-layout>
    <h1>Dashboard</h1>

    <h2>Repositories</h2>
    <a href="/new">Create new repo</a>

    <ul>
        @foreach ($repositories as $repository)
            <li>
                <a href="/{{ auth()->user()->username }}/{{ $repository->name }}">{{ $repository->name }}</a>
            </li>
        @endforeach
    </ul>
</x-app-layout>
