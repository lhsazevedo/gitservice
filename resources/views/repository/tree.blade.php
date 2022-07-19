<x-app-layout>
    <h1>{{ $user->username }}/{{ $repository->name }}/{{ $path }}</h1>

    <ul>
        @foreach ($items as $path => $item)
            @if ($item->type === 'tree')
                <li>
                    📁 <a href="/{{ $user->username }}/{{ $repository->name }}/tree/{{ $repository->default_branch }}/{{ $path }}">{{ pathinfo($path)['basename'] }}</a>
                </li>
            @elseif($item->type === 'blob')
                <li>
                    📄 <a href="/{{ $user->username }}/{{ $repository->name }}/blob/{{ $repository->default_branch }}/{{ $path }}">{{ pathinfo($path)['basename'] }}</a>
                </li>
            @endif
        @endforeach
    </ul>
</x-app-layout>