@extends('layouts.admin')

@section('page_title', 'Importar artículos')
@section('breadcrumb', 'Contenido / Importar artículos')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Importar artículos de actividades</h2>
                <p class="text-slate-600 mt-2">Este proceso trae artículos de turismo de diferentes países fuera de Europa y los guarda en la tabla posts con contenido y fotos compatibles con tu sistema.</p>
            </div>
            <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">
                <i class="fa-solid fa-arrow-left"></i>
                Ver artículos
            </a>
        </div>

        <form method="POST" action="{{ route('admin.posts.import.run') }}" class="space-y-6">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Cantidad de artículos</label>
                    <input type="number" name="limit" value="8" min="1" max="20" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
                    <p class="font-medium text-slate-800">Qué hará este script</p>
                    <ul class="list-disc pr-5 mt-2 space-y-1">
                        <li>Traerá artículos de turismo de fuera de Europa.</li>
                        <li>Los almacenará en español.</li>
                        <li>Guardar imágenes usando el sistema de medios actual.</li>
                        <li>Evitará duplicados por slug.</li>
                    </ul>
                </div>
            </div>

            <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-3 rounded-xl font-semibold hover:bg-blue-700">
                <i class="fa-solid fa-download"></i>
                Ejecutar importación
            </button>
        </form>
    </div>
</div>
@endsection
