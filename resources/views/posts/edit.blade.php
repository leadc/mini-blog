<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar post: {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 p-8">
                <form method="POST" action="{{ route('posts.update', $post) }}" class="space-y-6" id="post-form">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Título</label>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Contenido</label>
                        <div id="editorjs" class="border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 min-h-[200px] p-2"></div>
                        <input type="hidden" name="content" id="content-input">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-6 py-2 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.initEditor) window.initEditor(@json($post->content));
            const form = document.getElementById('post-form');
            form.addEventListener('submit', async function(e) {
                if (window.saveEditorContent) {
                    e.preventDefault();
                    await window.saveEditorContent();
                    form.submit();
                }
            });
        });
    </script>
</x-app-layout>
