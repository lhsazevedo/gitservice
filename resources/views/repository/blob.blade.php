<x-app-layout>
    <h1>{{ $user->username }}/{{ $repository->name }}/{{ $path }}</h1>

    <pre style="border: 1px solid black; padding: 8px;">{{ $content }}</pre>
</x-app-layout>