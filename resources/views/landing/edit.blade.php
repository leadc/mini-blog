@extends('layouts.app')

@section('content')
<div class="bg-white max-w-2xl mx-auto py-12 px-3">
    <h1 class="text-2xl font-bold mb-6">Editar textos de la landing</h1>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('landing.update') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @foreach(config('landing') as $key => $default)
            <div>
                <label class="block font-semibold mb-1" for="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                @if(str_contains($key, 'image'))
                    @if(!empty($data[$key]))
                        <div class="mb-2">
                            <img src="{{ $data[$key] }}" alt="Imagen actual" class="h-32 rounded shadow mb-1" style="max-width: 250px; max-height: 250px; object-fit: contain;">
                            <div>
                                <label class="inline-flex items-center text-sm">
                                    <input type="checkbox" name="delete_{{ $key }}" value="1" class="mr-1"> Eliminar imagen
                            </label>
                        </div>
                    </div>
                    @endif
                    <input type="file" name="{{ $key }}" id="{{ $key }}" accept="image/*" class="block w-full text-sm text-gray-600">
                @else
                    <textarea id="{{ $key }}" name="{{ $key }}" rows="{{ strlen($key) > 20 ? 3 : 1 }}" class="w-full border rounded p-2 @error($key) border-red-500 @enderror">{{ old($key, $data[$key] ?? $default) }}</textarea>
                @endif
                @error($key)
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        @endforeach
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-6 py-2 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Guardar cambios</button>
    </form>
</div>
@endsection
