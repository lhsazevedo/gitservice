<x-app-layout>
    <h1>{{ $user->username }}/{{ $repository->name }}/{{ $path }}</h1>

    <ul>
        @foreach ($items as $item)
            @if ($item['type'] === 'tree')
                <li>
                    📁 <a href="/{{ $user->username }}/{{ $repository->name }}/tree/master/{{ $item['path'] }}">{{ $item['basename'] }}</a>
                </li>
            @elseif($item['type'] === 'blob')
                <li>
                    📄 <a href="/{{ $user->username }}/{{ $repository->name }}/blob/master/{{ $item['path'] }}">{{ $item['basename'] }}</a>
                </li>
            @endif
        @endforeach
    </ul>
</x-app-layout>