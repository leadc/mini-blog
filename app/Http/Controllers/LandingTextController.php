<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingText;
use Illuminate\Support\Facades\Storage;

class LandingTextController extends Controller
{
    public function edit()
    {
        $defaults = config('landing');
        $texts = LandingText::all()->pluck('value', 'key')->toArray();
        // Mezclar valores de la base y defaults
        $data = array_merge($defaults, $texts);
        return view('landing.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $defaults = config('landing');
        $rules = [];
        foreach ($defaults as $key => $val) {
            if (str_contains($key, 'image')) {
                $rules[$key] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            } else {
                $rules[$key] = 'required';
            }
        }
        $validated = $request->validate($rules);
        foreach ($defaults as $key => $default) {
            // Imagen: subir/eliminar
            if (str_contains($key, 'image')) {
                $current = LandingText::where('key', $key)->value('value');
                // Eliminar imagen si se pide
                if ($request->has('delete_' . $key) && $current) {
                    $imgPath = public_path(parse_url($current, PHP_URL_PATH));
                    if (file_exists($imgPath)) {
                        @unlink($imgPath);
                    }
                    LandingText::updateOrCreate(['key' => $key], ['value' => '']);
                    $current = '';
                }
                // Subir nueva imagen
                if ($request->hasFile($key)) {
                    if ($current) {
                        $imgPath = public_path(parse_url($current, PHP_URL_PATH));
                        if (file_exists($imgPath)) {
                            @unlink($imgPath);
                        }
                    }
                    $file = $request->file($key);
                    $dir = public_path('storage/landing');
                    if (!is_dir($dir)) mkdir($dir, 0777, true);
                    $filename = uniqid('landing_') . '.' . $file->getClientOriginalExtension();
                    $file->move($dir, $filename);
                    $publicPath = '/public/storage/landing/' . $filename;
                    LandingText::updateOrCreate(['key' => $key], ['value' => $publicPath]);
                }
            } else {
                LandingText::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            }
        }
        return redirect()->route('landing.edit')->with('success', 'Textos actualizados correctamente.');
    }
}
