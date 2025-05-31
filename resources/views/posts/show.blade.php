@php
use App\Models\Post;
$latestPosts = Post::latest()->take(3)->get();
@endphp

<x-app-layout>
    <div class="bg-gradient-to-b from-pink-50 via-white to-white dark:bg-gray-900 min-h-screen pb-16">
        <div class="max-w-3xl mx-auto pt-12">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8 mb-5">
                @php
                    // Buscar la primera imagen del contenido como destacada
                    $featured = null;
                    if (isset($post->content['blocks'])) {
                        foreach ($post->content['blocks'] as $block) {
                            if ($block['type'] === 'image') {
                                $featured = $block['data']['file']['url'] ?? null;
                                break;
                            }
                        }
                    }
                @endphp
                @if($featured)
                    <div class="w-40 h-40 md:w-56 md:h-56 flex-shrink-0 rounded-2xl overflow-hidden shadow-lg bg-gray-200 dark:bg-gray-800">
                        <img src="{{ $featured }}" alt="" class="object-cover w-full h-full">
                    </div>
                @endif
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-2 leading-tight">{{ $post->title }}</h1>
                    <div class="text-gray-500 dark:text-gray-300 text-base mb-4">
                        Publicado por <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $post->user->name }}</span> el {{ $post->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            <div class="bg-white/80 dark:bg-gray-800/80 rounded-2xl shadow-xl p-8 md:p-12 mb-10">
                <div id="post-content" class="prose max-w-none prose-img:rounded-xl prose-img:shadow-lg prose-h3:text-2xl prose-h3:font-bold prose-p:text-lg prose-p:leading-relaxed prose-li:text-base prose-li:leading-relaxed prose-a:text-pink-600 dark:prose-a:text-pink-400"></div>
            </div>
            <!-- Más artículos -->
            <section class="mb-12">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Más artículos</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach($latestPosts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition overflow-hidden group border border-gray-100 dark:border-gray-700">
                        @php
                            $img = null;
                            if(isset($post->content['blocks'])) {
                                foreach($post->content['blocks'] as $block) {
                                    if($block['type'] === 'image' && isset($block['data']['file']['url'])) {
                                        $img = $block['data']['file']['url'];
                                        break;
                                    }
                                }
                            }
                        @endphp
                        @if($img)
                            <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                <img src="{{ $img }}" alt="Imagen del post" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="h-48 w-full bg-gradient-to-br from-pink-100 via-blue-100 to-yellow-100 dark:from-gray-700 dark:via-gray-800 dark:to-gray-700 flex items-center justify-center">
                                <svg class="w-16 h-16 text-pink-400 dark:text-pink-200 opacity-30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20l9-5-9-5-9 5 9 5z"/><path d="M12 12V4m0 0L3 9m9-5l9 5"/></svg>
                            </div>
                        @endif
                        <div class="p-5 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1 line-clamp-2">{{ $post->title }}</h3>
                            <div class="flex-1 text-sm text-gray-600 dark:text-gray-300 mb-2 line-clamp-3">
                                @if(isset($post->content['blocks']))
                                    @foreach($post->content['blocks'] as $block)
                                        @if($block['type'] === 'paragraph')
                                            {{ strip_tags($block['data']['text']) }}
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-400 mt-auto">
                                <span>{{ $post->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const data = @json($post->content);
            if (data && data.blocks) {
                const container = document.getElementById('post-content');
                data.blocks.forEach(block => {
                    if (block.type === 'header') {
                        container.innerHTML += `<h3>${block.data.text}</h3>`;
                    } else if (block.type === 'paragraph') {
                        container.innerHTML += `<p>${block.data.text}</p>`;
                    } else if (block.type === 'image') {
                        container.innerHTML += `<img src='${block.data.file.url}' alt='' class='my-6'/>`;
                    } else if (block.type === 'list') {
                        const tag = block.data.style === 'ordered' ? 'ol' : 'ul';
                        const items = block.data.items.map(item => `<li>${item.content}</li>`).join('');
                        container.innerHTML += `<${tag}>${items}</${tag}>`;
                    } else if (block.type === 'embed' && block.data.service === 'youtube') {
                        container.innerHTML += `<div class='my-6'><iframe width='100%' height='350' src='${block.data.embed}' frameborder='0' allowfullscreen class='rounded-xl shadow'></iframe></div>`;
                    }
                });
            }
        });
    </script>
</x-app-layout>
