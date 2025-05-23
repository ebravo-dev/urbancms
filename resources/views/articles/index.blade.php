<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Artículos') }}
            </h2>
            <a href="{{ route('articles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nuevo Artículo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($articles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">ID</th>
                                        <th class="py-2 px-4 border-b text-left">Título</th>
                                        <th class="py-2 px-4 border-b text-left">Imagen Principal</th>
                                        <th class="py-2 px-4 border-b text-left">Ficha Técnica</th>
                                        <th class="py-2 px-4 border-b text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $article)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $article->id }}</td>
                                            <td class="py-2 px-4 border-b">{{ $article->title }}</td>
                                            <td class="py-2 px-4 border-b">
                                                @if($article->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $article->images->first()->image_path) }}" 
                                                         alt="{{ $article->title }}" 
                                                         class="h-16 w-auto object-cover">
                                                @else
                                                    <span class="text-gray-400">Sin imagen</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                @if($article->datasheet_path)
                                                    <a href="{{ asset('storage/' . $article->datasheet_path) }}" 
                                                       target="_blank" 
                                                       class="text-blue-500 hover:underline">Ver ficha</a>
                                                @else
                                                    <span class="text-gray-400">Sin ficha</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('articles.edit', $article) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        Editar
                                                    </a>
                                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('¿Estás seguro de eliminar este artículo?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $articles->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No hay artículos disponibles.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
