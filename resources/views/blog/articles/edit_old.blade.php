<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Editar Artículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required value="{{ old('title', $article->title) }}">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="publication_date" class="block text-sm font-medium text-gray-700">Fecha de Publicación</label>
                            <input type="datetime-local" name="publication_date" id="publication_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('publication_date', $article->publication_date->format('Y-m-d\TH:i')) }}">
                            @error('publication_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Sección de SEO -->
                        <div class="mb-6 border-t border-b border-gray-200 py-4">
                            <h3 class="mb-4 text-lg font-medium text-gray-700">Herramientas SEO</h3>
                            
                            <div class="mb-4">
                                <label for="meta_title" class="flex items-center text-sm font-medium text-gray-700">
                                    Meta Título 
                                    <span class="ml-1 text-xs text-gray-500">(Recomendado: 50-60 caracteres)</span>
                                </label>
                                <input type="text" name="meta_title" id="meta_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('meta_title', $article->meta_title) }}" maxlength="70" oninput="updateSeoPreview()">
                                <div class="mt-1 flex justify-between">
                                    <span class="text-xs text-gray-500">Para motores de búsqueda</span>
                                    <span class="text-xs" id="meta_title_count">0/70</span>
                                </div>
                                @error('meta_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="meta_description" class="flex items-center text-sm font-medium text-gray-700">
                                    Meta Descripción
                                    <span class="ml-1 text-xs text-gray-500">(Recomendado: 150-160 caracteres)</span>
                                </label>
                                <textarea name="meta_description" id="meta_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" maxlength="160" oninput="updateSeoPreview()">{{ old('meta_description', $article->meta_description) }}</textarea>
                                <div class="mt-1 flex justify-between">
                                    <span class="text-xs text-gray-500">Breve descripción para resultados de búsqueda</span>
                                    <span class="text-xs" id="meta_description_count">0/160</span>
                                </div>
                                @error('meta_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="keywords" class="block text-sm font-medium text-gray-700">
                                    Palabras clave
                                    <span class="ml-1 text-xs text-gray-500">(Separadas por comas)</span>
                                </label>
                                <input type="text" name="keywords" id="keywords" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('keywords', $article->keywords) }}" placeholder="ejemplo, blog, artículo">
                                <p class="mt-1 text-xs text-gray-500">Ejemplo: inmobiliaria, casas en venta, terrenos</p>
                                @error('keywords')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            
                            <!-- Vista previa de cómo aparecerá en Google -->
                            <div class="mt-4">
                                <h4 class="mb-2 text-sm font-medium text-gray-700">Vista previa en Google:</h4>
                                <div class="rounded border border-gray-200 p-4">
                                    <div id="seo_preview_title" class="text-xl text-blue-600">{{ $article->meta_title ?: $article->title }}</div>
                                    <div class="text-green-700 text-sm">example.com/blog/{{ $article->slug }}</div>
                                    <div id="seo_preview_description" class="text-sm text-gray-700">{{ $article->meta_description ?: 'Meta descripción aquí...' }}</div>
                                </div>
                            </div>
                        </div>

                        @if($article->images->count() > 0)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Imágenes actuales</label>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4" id="images-container">
                                @foreach($article->images as $image)
                                <div class="relative border p-2 rounded" data-id="{{ $image->id }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Imagen del artículo" class="h-40 w-full object-cover">
                                    <form action="{{ route('article-images.destroy', $image) }}" method="POST" class="absolute top-2 right-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white p-1 rounded hover:bg-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="image_order" id="image-order">
                        </div>
                        @endif

                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700">Añadir nuevas imágenes</label>
                            <input type="file" name="images[]" id="images" class="mt-1 block w-full" multiple accept="image/*" onchange="previewNewImages(event)">
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <!-- Contenedor para la vista previa de imágenes nuevas -->
                            <div id="newImagePreviewContainer" class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"></div>
                        </div>

                        <div class="mb-4">
                            <label for="article-editor" class="block text-sm font-medium text-gray-700">Contenido del Artículo</label>
                            <div class="mb-2 flex flex-wrap">
                                <button type="button" class="px-2 py-1 m-1 bg-gray-200 rounded hover:bg-gray-300" onclick="addBlock('heading')">Añadir Encabezado</button>
                                <button type="button" class="px-2 py-1 m-1 bg-gray-200 rounded hover:bg-gray-300" onclick="addBlock('paragraph')">Añadir Párrafo</button>
                            </div>
                            
                            <!-- Editor basado en bloques -->
                            <div id="article-editor" class="mt-2 border rounded-md border-gray-300">
                                <!-- Los bloques se añadirán aquí dinámicamente -->
                                <div id="blocks-container" class="p-2">
                                    <!-- Contenedor de bloques dinámico -->
                                </div>
                                
                                <!-- Botón para agregar bloque al final -->
                                <div class="border-t border-gray-200 p-2 bg-gray-50 flex justify-center">
                                    <div class="inline-flex rounded-md" role="group">
                                        <button type="button" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-l-md" onclick="addBlock('paragraph', null)">
                                            Añadir Párrafo
                                        </button>
                                        <button type="button" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-r-md" onclick="addBlock('heading', null)">
                                            Añadir Encabezado
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Campo oculto para guardar el JSON del contenido -->
                            <input type="hidden" name="content" id="content-json" required>
                            
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-2 mb-4">
                            <h3 class="text-sm font-medium text-gray-700">Vista Previa</h3>
                            <div id="preview" class="p-4 mt-2 border rounded-md min-h-[100px] prose max-w-full">
                                <!-- La vista previa se generará dinámicamente -->
                            </div>
                        </div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('articles.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Actualizar Artículo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function insertFormat(format) {
            const textarea = document.getElementById('content');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            let replacement = '';
            
            // Si no hay texto seleccionado, insertamos texto predeterminado
            if (!selectedText) {
                switch(format) {
                    case 'h1':
                        replacement = `<h1>Encabezado</h1>`;
                        break;
                    case 'p':
                        replacement = `<p>Párrafo</p>`;
                        break;
                    case 'bold':
                        replacement = `<strong>texto en negrita</strong>`;
                        break;
                }
            } else {
                // Si hay texto seleccionado
                switch(format) {
                    case 'h1':
                        // Verificamos si ya tiene alguna etiqueta de texto
                        if (selectedText.includes('<p>') && selectedText.includes('</p>')) {
                            // Reemplazamos la etiqueta de párrafo por encabezado
                            replacement = selectedText.replace(/<p>(.*?)<\/p>/g, '<h1>$1</h1>');
                        } else {
                            replacement = `<h1>${selectedText}</h1>`;
                        }
                        break;
                    case 'p':
                        // Si ya tiene un encabezado, lo convertimos a párrafo
                        if (selectedText.includes('<h1>') && selectedText.includes('</h1>')) {
                            replacement = selectedText.replace(/<h1>(.*?)<\/h1>/g, '<p>$1</p>');
                        } else {
                            replacement = `<p>${selectedText}</p>`;
                        }
                        break;
                    case 'bold':
                        // Para negrita, mantenemos cualquier etiqueta que pueda tener
                        replacement = `<strong>${selectedText}</strong>`;
                        break;
                }
            }
            
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            updatePreview();
            
            // Set cursor position after inserted text
            const newCursorPosition = start + replacement.length;
            textarea.focus();
            textarea.setSelectionRange(newCursorPosition, newCursorPosition);
        }

        function updatePreview() {
            const content = document.getElementById('content').value;
            document.getElementById('preview').innerHTML = content;
            
            // Aplicar estilos CSS a elementos del HTML en la vista previa
            const preview = document.getElementById('preview');
            if (preview) {
                // Aseguramos que los estilos se apliquen después de renderizar el HTML
                setTimeout(() => {
                    const headings = preview.querySelectorAll('h1, h2, h3, h4, h5, h6');
                    headings.forEach(heading => {
                        if (heading.tagName === 'H1') {
                            heading.style.fontSize = '2em';
                            heading.style.fontWeight = 'bold';
                            heading.style.marginBottom = '0.5em';
                            heading.style.marginTop = '0.5em';
                        }
                    });
                    
                    const paragraphs = preview.querySelectorAll('p');
                    paragraphs.forEach(p => {
                        p.style.marginBottom = '1em';
                        
                        // Asegurarnos de que las negritas dentro de párrafos se renderizen bien
                        const strongs = p.querySelectorAll('strong');
                        strongs.forEach(strong => {
                            strong.style.fontWeight = 'bold';
                        });
                    });
                    
                    const strongs = preview.querySelectorAll('strong');
                    strongs.forEach(strong => {
                        strong.style.fontWeight = 'bold';
                    });
                }, 50);
            }
        }

        function updateSeoPreview() {
            const metaTitle = document.getElementById('meta_title').value.trim();
            const metaDescription = document.getElementById('meta_description').value.trim();
            
            // Actualizar el título de la vista previa SEO
            document.getElementById('seo_preview_title').innerText = metaTitle || 'Meta título aquí';
            
            // Actualizar la descripción de la vista previa SEO
            document.getElementById('seo_preview_description').innerText = metaDescription || 'Meta descripción aquí...';
        }
        
        function updateCharCount(inputId, counterId, maxLength) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            const currentLength = input.value.length;
            counter.innerText = `${currentLength}/${maxLength}`;
            
            // Cambiar color si se acerca o excede el límite
            if (currentLength > maxLength) {
                counter.classList.add('text-red-600');
                counter.classList.remove('text-gray-500', 'text-yellow-600');
            } else if (currentLength > (maxLength * 0.8)) {
                counter.classList.add('text-yellow-600');
                counter.classList.remove('text-gray-500', 'text-red-600');
            } else {
                counter.classList.add('text-gray-500');
                counter.classList.remove('text-red-600', 'text-yellow-600');
            }
        }

        // Update preview on page load and content change
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('content').addEventListener('input', updatePreview);
            updatePreview();
            updateSeoPreview(); // Inicializar la vista previa SEO
            
            // Agregar event listeners para los campos SEO
            document.getElementById('meta_title').addEventListener('input', function() {
                updateSeoPreview();
                updateCharCount('meta_title', 'meta_title_count', 70);
            });
            document.getElementById('meta_description').addEventListener('input', function() {
                updateSeoPreview();
                updateCharCount('meta_description', 'meta_description_count', 160);
            });
            
            // Inicializar contadores de caracteres
            updateCharCount('meta_title', 'meta_title_count', 70);
            updateCharCount('meta_description', 'meta_description_count', 160);
            
            // Initialize sortable for image reordering
            if (document.getElementById('images-container')) {
                const sortable = new Sortable(document.getElementById('images-container'), {
                    animation: 150,
                    ghostClass: 'bg-gray-100',
                    onEnd: function() {
                        updateImageOrder();
                    }
                });
                
                updateImageOrder();
            }
        });

        function updateImageOrder() {
            const container = document.getElementById('images-container');
            if (!container) return;
            
            const images = container.querySelectorAll('[data-id]');
            const order = Array.from(images).map(img => img.dataset.id);
            
            document.getElementById('image-order').value = order.join(',');
            
            // Send order to backend
            fetch('{{ route("articles.reorder-images", $article) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ images: order })
            });
        }
        
        // Función para mostrar vista previa de las nuevas imágenes seleccionadas
        function previewNewImages(event) {
            const container = document.getElementById('newImagePreviewContainer');
            container.innerHTML = ''; // Limpiar cualquier vista previa anterior
            
            const files = event.target.files;
            if (!files || files.length === 0) return;
            
            // Procesar cada archivo seleccionado
            Array.from(files).forEach(file => {
                // Solo procesar si es imagen
                if (!file.type.startsWith('image/')) return;
                
                // Crear el contenedor para la imagen
                const imgContainer = document.createElement('div');
                imgContainer.className = 'relative border p-2 rounded';
                
                // Crear la imagen
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'h-40 w-full object-cover rounded';
                img.onload = function() {
                    // Liberar memoria cuando la imagen se ha cargado
                    URL.revokeObjectURL(img.src);
                };
                
                // Nombre del archivo
                const fileName = document.createElement('p');
                fileName.className = 'mt-1 text-sm text-gray-500 truncate';
                fileName.textContent = file.name;
                
                // Añadir elementos al DOM
                imgContainer.appendChild(img);
                imgContainer.appendChild(fileName);
                container.appendChild(imgContainer);
            });
        }
    </script>
    @endpush
</x-app-layout>
