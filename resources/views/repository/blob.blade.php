<x-app-layout>
  <div class="container py-4">
    <h1 class="mb-4">{{ $user->username }}/{{ $repository->name }}/{{ $path }}</h1>

    <div class="card p-4">
      <pre>{{ $content }}</pre>
    </div>
  </div>
</x-app-layout>