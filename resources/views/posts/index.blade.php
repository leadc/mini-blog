<x-app-layout>
    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($posts as $post)
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
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
