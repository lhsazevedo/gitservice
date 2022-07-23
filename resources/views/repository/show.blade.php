<x-app-layout>
    <h1>{{ $user->username }}/{{ $repository->name }}</h1>

    <form method="POST">
        @method('DELETE')
        @csrf

        <button>Excluir repositório</button>
    </form>

    <h2>Files</h2>
    <ul>
        @foreach ($items as $item)
            @if ($item->obj->type === 'tree')
                <li>
                    📁 <a href="/{{ $user->username }}/{{ $repository->name }}/tree/{{ $repository->default_branch }}/{{ $item->path }}">{{ pathinfo($item->path)['basename'] }}</a>: {{ $item->latestCommitMessage($repository->default_branch) }}
                </li>
            @elseif($item->obj->type === 'blob')
                <li>
                    📄 <a href="/{{ $user->username }}/{{ $repository->name }}/blob/{{ $repository->default_branch }}/{{ $item->path }}">{{ pathinfo($item->path)['basename'] }}</a>: {{ $item->latestCommitMessage($repository->default_branch) }}
                </li>
            @endif
        @endforeach
    </ul>
</x-app-layout>