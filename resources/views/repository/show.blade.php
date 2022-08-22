<x-app-layout>
  <div class="container py-4">
    <h1 class="mb-4">{{ $user->username }}/{{ $repository->name }}</h1>

    <form class="mb-4" method="POST">
        @method('DELETE')
        @csrf

        <button class="btn btn-light">Excluir repositório</button>
    </form>
    
    <div class="card">
      @if ($isEmpty)
        <!-- TODO: Improve message -->
        Repositório vazio
      @else
        <table class="table table-sm w-100 mb-0">
          <tbody>
            @foreach ($items as $item)
              <tr>
                @if ($item->obj->type === 'tree')
                    <td>
                        <span class="mx-2">📁</span><a class="link-dark text-decoration-none" href="/{{ $user->username }}/{{ $repository->name }}/tree/{{ $repository->default_branch }}/{{ $item->path }}">{{ pathinfo($item->path)['basename'] }}</a>
                    </td>
                @elseif($item->obj->type === 'blob')
                    <td>
                        <span class="mx-2">📄</span><a class="link-dark text-decoration-none" href="/{{ $user->username }}/{{ $repository->name }}/blob/{{ $repository->default_branch }}/{{ $item->path }}">{{ pathinfo($item->path)['basename'] }}</a>
                    </td>
                @endif

                <td class="text-muted">
                  {{ $item->latestCommitMessage($repository->default_branch) }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>
</x-app-layout>
