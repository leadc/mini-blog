@php
use App\Models\Post;
$latestPosts = Post::latest()->take(3)->get();
$landing = array_merge(config('landing'), \App\Models\LandingText::all()->pluck('value', 'key')->toArray());
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Blog</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-gray-900 min-h-screen">
    <div class="w-full max-w-5xl mx-auto py-4 px-4">
        @include('layouts.navigation')
    </div>
    <header class="w-full max-w-5xl mx-auto flex flex-col md:flex-row items-center gap-8 py-12 px-4">
        <div class="flex-shrink-0 flex flex-col items-center md:items-start">
            <img src="{{ $landing['header_image'] }}" alt="Foto de perfil" class="w-56 h-56 object-cover rounded-xl shadow-lg mb-4" onerror="this.style.display='none'">
        </div>
        <div class="flex-1 flex flex-col items-center md:items-start">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-2 leading-tight">{!! $landing['title'] !!}</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-4 max-w-xl">{{ $landing['intro'] }}</p>
            <a href="#about" class="inline-block px-6 py-2 bg-pink-600 text-white rounded-lg font-semibold shadow hover:bg-pink-700 transition mb-2">{{ $landing['about_button'] }}</a>
        </div>
    </header>
    <main class="w-full max-w-5xl mx-auto px-4">
        <section class="mb-12">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $landing['articles_title'] }}</h2>
                <a href="{{ route('posts.index') }}" class="text-sm text-pink-600 hover:underline font-semibold">{{ $landing['see_all'] }}</a>
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
        <section id="about" class="mb-16">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">{{ $landing['about_title'] }}</h2>
            <div class="bg-white/80 dark:bg-gray-800/80 rounded-lg shadow p-6 text-gray-700 dark:text-gray-200">
                <p>{{ $landing['about_text'] }}</p>
            </div>
        </section>
    </main>
    <footer class="w-full max-w-5xl mx-auto py-8 text-center text-gray-400 text-xs">
        <hr>
        <div class="mt-8">
            &copy; {{ date('Y') }} {{ $landing['footer'] }}
        </div>
    </footer>
</body>
</html>
