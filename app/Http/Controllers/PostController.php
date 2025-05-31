<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Endpoint para subir imágenes desde Editor.js
     */
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Guardar directamente en public/storage/posts
            $path = $file->store('posts', ['disk' => 'custom_public']);
            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => '/public/storage/posts/' . basename($path)
                ]
            ]);
        }
        return response()->json(['success' => 0]);
    }

    /**
     * Endpoint para eliminar imágenes desde Editor.js
     */
    public function deleteImage(Request $request)
    {
        $url = $request->input('url');
        if ($url) {
            $path = public_path(parse_url($url, PHP_URL_PATH));
            if (file_exists($path)) {
                @unlink($path);
                return response()->json(['success' => 1]);
            }
        }
        return response()->json(['success' => 0]);
    }

    /**
     * Eliminar un post.
     */
    public function destroy(Post $post)
    {
        // Eliminar imágenes asociadas al post
        if (isset($post->content['blocks'])) {
            foreach ($post->content['blocks'] as $block) {
                if ($block['type'] === 'image' && !empty($block['data']['file']['url'])) {
                    $imgPath = public_path(parse_url($block['data']['file']['url'], PHP_URL_PATH));
                    if (file_exists($imgPath)) {
                        @unlink($imgPath);
                    }
                }
            }
        }
        $post->delete();
        return redirect()->route('dashboard')->with('status', 'Post eliminado correctamente');
    }

    /**
     * Guardar un nuevo post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);
        $content = json_decode($request->input('content'), true);
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'content' => $content,
        ]);
        return redirect()->route('dashboard')->with('status', 'Post creado correctamente');
    }

    /**
     * Mostrar un post público.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Mostrar el formulario de edición de un post.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Actualizar un post existente.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);
        $content = $request->input('content');
        if (is_string($content)) {
            $content = json_decode($content, true);
        }
        $post->update([
            'title' => $request->input('title'),
            'content' => $content,
        ]);
        return redirect()->route('dashboard')->with('status', 'Post actualizado correctamente');
    }
}
