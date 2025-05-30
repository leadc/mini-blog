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
            $path = $file->store('posts', 'public');
            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => Storage::url($path)
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
            // Obtener el path relativo a storage/app/public
            $publicPath = parse_url($url, PHP_URL_PATH);
            $storagePath = str_replace('/storage/', '', $publicPath);
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
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
        // Solo el dueño o admin puede borrar
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }
        // Eliminar imágenes asociadas en el contenido
        $content = $post->content;
        if (is_array($content) && isset($content['blocks'])) {
            foreach ($content['blocks'] as $block) {
                if ($block['type'] === 'image' && isset($block['data']['file']['url'])) {
                    $url = $block['data']['file']['url'];
                    $publicPath = parse_url($url, PHP_URL_PATH);
                    $storagePath = str_replace('/storage/', '', $publicPath);
                    if (Storage::disk('public')->exists($storagePath)) {
                        Storage::disk('public')->delete($storagePath);
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
