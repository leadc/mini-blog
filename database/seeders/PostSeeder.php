<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Asume que hay al menos un usuario (admin)
        if (!$user) return;

        $content = [
            'time' => now()->timestamp * 1000,
            'blocks' => [
                [
                    'type' => 'header',
                    'data' => [
                        'text' => 'Ejemplo de post',
                        'level' => 2
                    ]
                ],
                [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => 'Este es un post de ejemplo generado por el seeder. Puedes editar o eliminar este post desde el dashboard.'
                    ]
                ],
                [
                    'type' => 'list',
                    'data' => [
                        'style' => 'unordered',
                        'items' => [
                            'Elemento 1',
                            'Elemento 2',
                            'Elemento 3'
                        ]
                    ]
                ],
                [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => '¡Bienvenido al mini blog!'
                    ]
                ]
            ],
            'version' => '2.29.1'
        ];

        for ($i = 1; $i <= 150; $i++) {
            Post::create([
                'user_id' => $user->id,
                'title' => "Post de ejemplo #$i",
                'content' => $content,
            ]);
        }
    }
}
