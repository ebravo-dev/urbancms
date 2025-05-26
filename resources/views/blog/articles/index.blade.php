<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Artículos del Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Listado de Artículos</h3>
                        <a href="{{ route('articles.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Nuevo Artículo
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 text-sm bg-green-100 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Título</th>
                                    <th class="py-2 px-4 border-b text-left">Fecha de Publicación</th>
                                    <th class="py-2 px-4 border-b text-left">Comentarios</th>
                                    <th class="py-2 px-4 border-b text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($articles as $article)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $article->title }}</td>
                                        <td class="py-2 px-4 border-b">{{ $article->publication_date->format('d/m/Y H:i') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $article->comments()->count() }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('articles.show', $article) }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                                    Ver
                                                </a>
                                                <a href="{{ route('articles.edit', $article) }}" class="px-3 py-1 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                                                    Editar
                                                </a>
                                                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro que deseas eliminar este artículo?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                                            No hay artículos disponibles.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
