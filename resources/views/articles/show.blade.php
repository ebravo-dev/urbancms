<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $article->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('articles.edit', $article) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Editar
                </a>
                <a href="{{ route('articles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    @if($article->images->count() > 0)
                        <div class="mb-6">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="relative aspect-video overflow-hidden rounded-lg bg-gray-100">
                                    <img id="main-image" src="{{ asset('storage/' . $article->images->first()->image_path) }}" 
                                        alt="{{ $article->title }}" 
                                        class="h-full w-full object-cover">
                                </div>
                                
                                @if($article->images->count() > 1)
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach($article->images as $image)
                                            <div class="w-24 h-24 cursor-pointer overflow-hidden rounded-lg hover:ring-2 hover:ring-indigo-500" 
                                                onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                    alt="{{ $article->title }}" 
                                                    class="h-full w-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="prose max-w-none">
                        <h1 class="text-2xl font-bold mb-4">{{ $article->title }}</h1>
                        <div class="space-y-4">
                            {!! nl2br(e($article->description)) !!}
                        </div>
                    </div>
                </div>
            </div>

            @if($article->datasheet_path)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold mb-4">Ficha Técnica</h2>
                        
                        <a href="{{ asset('storage/' . $article->datasheet_path) }}" 
                           target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Descargar Ficha Técnica
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function changeMainImage(src) {
            document.getElementById('main-image').src = src;
        }
    </script>
</x-app-layout>