<x-app-layout>
    <h1>Create new Repository</h1>

    <form method="POST">
        @csrf

        <input type="text" name="name">
        <button>Create</button>
    </form>
</x-app-layout>
