<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Ver Artículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <h1 class="text-2xl font-bold">{{ $article->title }}</h1>
                            <div>
                                <a href="{{ route('articles.edit', $article) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                                    Editar
                                </a>
                                <a href="{{ route('articles.index') }}" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    Volver
                                </a>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">Publicado: {{ $article->publication_date->format('d/m/Y H:i') }}</p>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 text-sm bg-green-100 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($article->images->count() > 0)
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($article->images as $image)
                            <div class="border p-2 rounded">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Imagen del artículo" class="h-40 w-full object-cover">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-8 prose max-w-full" id="article-content">
                        <!-- El contenido del artículo se renderizará aquí mediante JS -->
                    </div>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Convertir la cadena JSON a objeto JavaScript
                            const contentBlocks = @json($article->content ?? []);
                            const contentContainer = document.getElementById('article-content');
                            
                            // Renderizar cada bloque de contenido
                            if (Array.isArray(contentBlocks)) {
                                contentBlocks.forEach(block => {
                                    let element;
                                    
                                    // Crear el elemento según el tipo de bloque
                                    switch (block.type) {
                                        case 'heading':
                                            element = document.createElement('h1');
                                            element.className = 'text-2xl font-bold my-3';
                                            element.textContent = block.content;
                                            break;
                                            
                                        case 'paragraph':
                                            element = document.createElement('p');
                                            element.className = 'mb-4';
                                            element.textContent = block.content;
                                            break;
                                            
                                        case 'quote':
                                            element = document.createElement('blockquote');
                                            element.className = 'pl-4 border-l-4 border-gray-300 italic my-4';
                                            element.textContent = block.content;
                                            break;
                                            
                                        case 'list':
                                            element = document.createElement('ul');
                                            element.className = 'list-disc pl-5 mb-4';
                                            
                                            // Dividir por líneas y crear elementos de lista
                                            const items = block.content.split('\n').filter(item => item.trim() !== '');
                                            items.forEach(item => {
                                                const li = document.createElement('li');
                                                li.textContent = item;
                                                li.className = 'mb-1';
                                                element.appendChild(li);
                                            });
                                            break;
                                            
                                        case 'subtitle':
                                            element = document.createElement('h2');
                                            element.className = 'text-xl font-semibold my-2';
                                            element.textContent = block.content;
                                            break;
                                            
                                        default:
                                            element = document.createElement('p');
                                            element.className = 'mb-4';
                                            element.textContent = block.content;
                                    }
                                    
                                    if (element) {
                                        contentContainer.appendChild(element);
                                    }
                                });
                            } else {
                                // Por compatibilidad con el contenido antiguo (HTML)
                                contentContainer.innerHTML = `<div class="bg-yellow-50 p-4 rounded mb-4">
                                    <p class="text-amber-700">Este artículo usa un formato antiguo y podría no mostrarse correctamente.</p>
                                </div>
                                ${contentBlocks}`;
                            }
                        });
                    </script>

                    <hr class="my-8">

                    <div>
                        <h3 class="text-xl font-bold mb-4">Comentarios ({{ $article->comments->count() }})</h3>

                        <div class="mb-6">
                            <form action="{{ route('comments.store', $article) }}" method="POST" class="bg-gray-50 p-4 rounded-md">
                                @csrf
                                <h4 class="font-semibold mb-2">Dejar un comentario</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required value="{{ old('name') }}">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required value="{{ old('email') }}">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="message" class="block text-sm font-medium text-gray-700">Mensaje</label>
                                    <textarea name="message" id="message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Publicar Comentario
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="space-y-4">
                            @forelse ($article->comments as $comment)
                            <div class="p-4 border rounded-md {{ $comment->is_approved ? 'bg-white' : 'bg-red-50' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h5 class="font-semibold">{{ $comment->name }}</h5>
                                        <p class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <form action="{{ route('comments.toggle-approval', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 {{ $comment->is_approved ? 'bg-yellow-600' : 'bg-green-600' }} text-white rounded-md hover:{{ $comment->is_approved ? 'bg-yellow-700' : 'bg-green-700' }}">
                                                {{ $comment->is_approved ? 'Desaprobar' : 'Aprobar' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro que deseas eliminar este comentario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p>{{ $comment->message }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="p-4 bg-gray-50 rounded-md text-center">
                                <p class="text-gray-500">No hay comentarios aún.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
