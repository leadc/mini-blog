<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Posts actuales</h3>
                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center">
                            <label for="perPage" class="mr-2 text-sm text-gray-600 dark:text-gray-300">Mostrar</label>
                            <select name="perPage" id="perPage" style="min-width: 3.5rem;" class="border-gray-300 dark:border-gray-700 rounded px-2 py-1 text-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100" onchange="this.form.submit()">
                                <option value="10" {{ request('perPage', 25) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('perPage', 25) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('perPage', 25) == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </form>
                    </div>
                    <ul>
                        @foreach($posts as $post)
                            <li class="mb-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">#{{ $post->id }} - {{ $post->title }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500">{{ $post->created_at->format('d/m/Y H:i') }}</span>
                                        <a href="{{ route('posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold">Editar</a>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-2 text-red-600 hover:text-red-800 text-xs font-bold">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if($posts->count() === 0)
                        <p class="text-gray-500">No hay posts guardados.</p>
                    @endif
                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
