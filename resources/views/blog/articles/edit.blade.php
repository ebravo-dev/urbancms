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
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="image_order" id="image-order">
                        </div>
                        @endif

                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700">Añadir nuevas imágenes</label>
                            <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                <p class="text-sm text-blue-800">
                                    <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <strong>Optimización automática:</strong> Las imágenes se convertirán automáticamente a formato WebP para mejor rendimiento web. Tamaño máximo: 1200x800px, calidad: 85%.
                                </p>
                            </div>
                            <input type="file" name="images[]" id="images" class="mt-1 block w-full" multiple accept="image/*,.webp" onchange="previewNewImages(event)">
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
                                <button type="button" class="px-2 py-1 m-1 bg-gray-200 rounded hover:bg-gray-300" onclick="addBlock('subtitle')">Añadir Subtítulo</button>
                                <button type="button" class="px-2 py-1 m-1 bg-gray-200 rounded hover:bg-gray-300" onclick="addBlock('paragraph')">Añadir Párrafo</button>
                                <button type="button" class="px-2 py-1 m-1 bg-gray-200 rounded hover:bg-gray-300" onclick="addBlock('quote')">Añadir Cita</button>
                                <button type="button" class="px-2 py-1 m-1 bg-gray-200 rounded hover:bg-gray-300" onclick="addBlock('list')">Añadir Lista</button>
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
                                        <button type="button" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200" onclick="addBlock('heading', null)">
                                            Añadir Encabezado
                                        </button>
                                        <button type="button" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200" onclick="addBlock('subtitle', null)">
                                            Añadir Subtítulo
                                        </button>
                                        <button type="button" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200" onclick="addBlock('quote', null)">
                                            Añadir Cita
                                        </button>
                                        <button type="button" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-r-md" onclick="addBlock('list', null)">
                                            Añadir Lista
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

                        <div class="flex justify-between">
                            <a href="{{ route('articles.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700" onclick="prepareContentJSON(); updateImageOrder();">
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
        // Variables globales para el editor de bloques
        let blockCounter = 0;
        let contentBlocks = [];
        const existingContent = @json($article->content ?? []);
        
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar los bloques con el contenido existente o un párrafo vacío
            initBlocksEditor();
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
            
            // Inicializar evento para guardar formulario
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Evento submit disparado...'); // Debug
                    
                    // Preparar contenido JSON
                    prepareContentJSON();
                    
                    // Actualizar orden de imágenes una vez más antes del envío
                    updateImageOrder();
                    
                    console.log('Formulario listo para enviar');
                });
            }
            
            // Initialize sortable for image reordering
            if (document.getElementById('images-container')) {
                updateImageOrderField(); // Llamar a la función inicial
                
                const sortable = new Sortable(document.getElementById('images-container'), {
                    animation: 150,
                    ghostClass: 'bg-gray-100',
                    onEnd: function() {
                        updateImageOrder();
                    }
                });
            }
        });
        
        // Función para actualizar el orden de las imágenes
        function updateImageOrderField() {
            const container = document.getElementById('images-container');
            if (!container) return;
            
            const imageContainers = document.querySelectorAll('#images-container > div');
            const imageIds = Array.from(imageContainers).map(container => container.dataset.id);
            const orderField = document.getElementById('image-order');
            if (orderField) {
                orderField.value = imageIds.join(','); // Cambiar a join(',') para consistencia
                console.log('Orden inicial de imágenes:', imageIds.join(',')); // Debug
            }
        }
        
        // Inicializar editor de bloques
        function initBlocksEditor() {
            // Primero verificamos si hay contenido existente
            if (Array.isArray(existingContent) && existingContent.length > 0) {
                // Mapear el contenido existente al formato de bloque interno
                contentBlocks = existingContent.map((block, index) => ({
                    id: 'block-' + blockCounter++,
                    type: block.type || 'paragraph',
                    content: block.content || ''
                }));
            } else if (typeof existingContent === 'string') {
                // Si el contenido existente es un string (HTML antiguo), creamos un bloque de párrafo con él
                contentBlocks = [{
                    id: 'block-' + blockCounter++,
                    type: 'paragraph',
                    content: existingContent
                }];
            } else {
                // Si no hay contenido, añadimos un párrafo vacío por defecto
                addBlock('paragraph');
            }
            
            renderBlocks();
            updatePreview();
        }
        
        // Añadir un nuevo bloque al editor
        function addBlock(type, afterBlockId = null) {
            const blockId = 'block-' + blockCounter++;
            let defaultContent = '';
            
            // Contenido por defecto según el tipo
            switch(type) {
                case 'heading':
                    defaultContent = 'Título Nuevo';
                    break;
                case 'paragraph':
                    defaultContent = 'Escribe aquí tu contenido...';
                    break;
                case 'quote':
                    defaultContent = 'Escribe aquí una cita...';
                    break;
                case 'list':
                    defaultContent = 'Elemento 1\nElemento 2\nElemento 3';
                    break;
                case 'subtitle':
                    defaultContent = 'Subtítulo';
                    break;
                default:
                    defaultContent = 'Escribe aquí...';
            }
            
            const block = {
                id: blockId,
                type: type,
                content: defaultContent
            };
            
            // Si afterBlockId es null, añadir al final
            // Si no, insertar después del bloque especificado
            if (afterBlockId === null) {
                contentBlocks.push(block);
            } else {
                const index = contentBlocks.findIndex(b => b.id === afterBlockId);
                if (index !== -1) {
                    contentBlocks.splice(index + 1, 0, block);
                } else {
                    contentBlocks.push(block);
                }
            }
            
            renderBlocks();
            updatePreview();
        }
        
        // Eliminar un bloque
        function removeBlock(blockId) {
            const index = contentBlocks.findIndex(block => block.id === blockId);
            if (index !== -1) {
                contentBlocks.splice(index, 1);
                renderBlocks();
                updatePreview();
                
                // Si no quedan bloques, añadir uno por defecto
                if (contentBlocks.length === 0) {
                    addBlock('paragraph');
                }
            }
        }
        
        // Actualizar el contenido de un bloque
        function updateBlockContent(blockId, content) {
            const block = contentBlocks.find(block => block.id === blockId);
            if (block) {
                block.content = content;
                updatePreview();
            }
        }
        
        // Cambiar tipo de bloque
        function changeBlockType(blockId, newType) {
            const block = contentBlocks.find(block => block.id === blockId);
            if (block) {
                block.type = newType;
                renderBlocks();
                updatePreview();
            }
        }
        
        // Renderizar todos los bloques en el editor
        function renderBlocks() {
            const container = document.getElementById('blocks-container');
            container.innerHTML = '';
            
            contentBlocks.forEach((block, index) => {
                const blockElement = document.createElement('div');
                blockElement.className = 'mb-3 p-2 border border-gray-300 rounded';
                blockElement.dataset.blockId = block.id;
                
                // Crear barra de herramientas para el bloque
                const toolbar = document.createElement('div');
                toolbar.className = 'flex justify-between items-center mb-2 pb-1 border-b border-gray-200';
                
                // Tipo de bloque
                const typeLabel = document.createElement('span');
                typeLabel.className = 'text-xs font-medium text-gray-500';
                typeLabel.textContent = getBlockTypeName(block.type);
                
                // Botones de acción
                const actions = document.createElement('div');
                actions.className = 'flex space-x-1';
                
                // Botón para cambiar tipo
                const changeTypeBtn = document.createElement('button');
                changeTypeBtn.type = 'button';
                changeTypeBtn.className = 'text-xs px-1 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded';
                changeTypeBtn.textContent = 'Cambiar tipo';
                changeTypeBtn.onclick = function(e) {
                    e.preventDefault();
                    showTypeSelector(block.id, e.target);
                };
                
                // Botón para eliminar
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'text-xs px-1 bg-red-100 text-red-600 hover:bg-red-200 rounded';
                removeBtn.textContent = 'Eliminar';
                removeBtn.onclick = function(e) {
                    e.preventDefault();
                    removeBlock(block.id);
                };
                
                actions.appendChild(changeTypeBtn);
                actions.appendChild(removeBtn);
                
                toolbar.appendChild(typeLabel);
                toolbar.appendChild(actions);
                
                // Crear el campo de entrada según el tipo
                let inputField;
                
                switch(block.type) {
                    case 'heading':
                        inputField = document.createElement('input');
                        inputField.type = 'text';
                        inputField.className = 'w-full p-2 text-xl font-bold border-0 focus:ring-0 focus:outline-none';
                        break;
                    case 'paragraph':
                        inputField = document.createElement('textarea');
                        inputField.className = 'w-full p-2 border-0 focus:ring-0 focus:outline-none resize-none';
                        inputField.rows = 3;
                        break;
                    case 'quote':
                        inputField = document.createElement('textarea');
                        inputField.className = 'w-full p-2 border-0 focus:ring-0 focus:outline-none resize-none bg-gray-50 italic';
                        inputField.rows = 2;
                        break;
                    case 'list':
                        inputField = document.createElement('textarea');
                        inputField.className = 'w-full p-2 border-0 focus:ring-0 focus:outline-none resize-none';
                        inputField.rows = 4;
                        inputField.placeholder = 'Escribe cada elemento en una línea';
                        break;
                    case 'subtitle':
                        inputField = document.createElement('input');
                        inputField.type = 'text';
                        inputField.className = 'w-full p-2 text-lg font-semibold border-0 focus:ring-0 focus:outline-none';
                        break;
                    default:
                        inputField = document.createElement('textarea');
                        inputField.className = 'w-full p-2 border-0 focus:ring-0 focus:outline-none resize-none';
                        inputField.rows = 3;
                }
                
                inputField.value = block.content;
                
                // Ajustar altura de textarea
                if (inputField.tagName === 'TEXTAREA') {
                    inputField.oninput = function() {
                        this.style.height = 'auto';
                        this.style.height = (this.scrollHeight) + 'px';
                    };
                }
                
                // Asignar eventos al campo de entrada
                inputField.dataset.blockId = block.id;
                inputField.addEventListener('input', function() {
                    updateBlockContent(this.dataset.blockId, this.value);
                });
                
                // Botón para añadir bloque después de este
                const addBlockAfter = document.createElement('div');
                addBlockAfter.className = 'flex justify-center mt-2 pt-1 border-t border-gray-200';
                
                const addBlockBtn = document.createElement('button');
                addBlockBtn.type = 'button';
                addBlockBtn.className = 'text-xs px-2 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded';
                addBlockBtn.textContent = '+ Insertar bloque después';
                addBlockBtn.onclick = function(e) {
                    e.preventDefault();
                    showBlockTypeMenu(block.id, e.target);
                };
                
                addBlockAfter.appendChild(addBlockBtn);
                
                blockElement.appendChild(toolbar);
                blockElement.appendChild(inputField);
                blockElement.appendChild(addBlockAfter);
                
                container.appendChild(blockElement);
                
                // Ajustar altura de textarea si es necesario
                if (inputField.tagName === 'TEXTAREA') {
                    inputField.style.height = 'auto';
                    inputField.style.height = (inputField.scrollHeight) + 'px';
                }
            });
        }
        
        // Mostrar menú para seleccionar tipo de bloque para insertar
        function showBlockTypeMenu(blockId, targetElement) {
            const menu = document.createElement('div');
            menu.className = 'absolute bg-white shadow-lg rounded border border-gray-200 z-10';
            
            const blockTypes = [
                { type: 'paragraph', name: 'Párrafo' },
                { type: 'heading', name: 'Encabezado' },
                { type: 'subtitle', name: 'Subtítulo' },
                { type: 'quote', name: 'Cita' },
                { type: 'list', name: 'Lista' }
            ];
            
            blockTypes.forEach(blockType => {
                const button = document.createElement('button');
                button.className = 'block w-full text-left px-4 py-2 hover:bg-gray-100';
                button.textContent = blockType.name;
                button.onclick = function(e) {
                    e.preventDefault();
                    addBlock(blockType.type, blockId);
                    menu.remove();
                };
                menu.appendChild(button);
            });
            
            // Añadir al DOM cerca del elemento target
            targetElement.parentNode.appendChild(menu);
            
            // Posicionar el menú
            const rect = targetElement.getBoundingClientRect();
            menu.style.top = `${rect.bottom + window.scrollY}px`;
            menu.style.left = `${rect.left + window.scrollX}px`;
            
            // Cerrar al hacer clic fuera
            document.addEventListener('click', function closeMenu(e) {
                if (!menu.contains(e.target) && e.target !== targetElement) {
                    menu.remove();
                    document.removeEventListener('click', closeMenu);
                }
            });
        }
        
        // Mostrar selector para cambiar tipo de bloque
        function showTypeSelector(blockId, targetElement) {
            const menu = document.createElement('div');
            menu.className = 'absolute bg-white shadow-lg rounded border border-gray-200 z-10';
            
            const blockTypes = [
                { type: 'paragraph', name: 'Párrafo' },
                { type: 'heading', name: 'Encabezado' },
                { type: 'subtitle', name: 'Subtítulo' },
                { type: 'quote', name: 'Cita' },
                { type: 'list', name: 'Lista' }
            ];
            
            blockTypes.forEach(blockType => {
                const button = document.createElement('button');
                button.className = 'block w-full text-left px-4 py-2 hover:bg-gray-100';
                button.textContent = blockType.name;
                button.onclick = function(e) {
                    e.preventDefault();
                    changeBlockType(blockId, blockType.type);
                    menu.remove();
                };
                menu.appendChild(button);
            });
            
            // Añadir al DOM cerca del elemento target
            targetElement.parentNode.appendChild(menu);
            
            // Posicionar el menú
            const rect = targetElement.getBoundingClientRect();
            menu.style.top = `${rect.bottom + window.scrollY}px`;
            menu.style.left = `${rect.left + window.scrollX}px`;
            
            // Cerrar al hacer clic fuera
            document.addEventListener('click', function closeMenu(e) {
                if (!menu.contains(e.target) && e.target !== targetElement) {
                    menu.remove();
                    document.removeEventListener('click', closeMenu);
                }
            });
        }
        
        // Obtener nombre legible para tipo de bloque
        function getBlockTypeName(type) {
            const types = {
                'paragraph': 'Párrafo',
                'heading': 'Encabezado',
                'subtitle': 'Subtítulo',
                'quote': 'Cita',
                'list': 'Lista'
            };
            return types[type] || 'Bloque';
        }
        
        // Preparar el JSON de contenido para enviar al servidor
        function prepareContentJSON() {
            console.log('Preparando JSON...', contentBlocks); // Debug
            
            // Crear una copia limpia de los bloques sin las propiedades internas
            const cleanBlocks = contentBlocks.map(block => ({
                type: block.type,
                content: block.content
            }));
            
            // Convertir a JSON y guardar en el campo oculto
            const json = JSON.stringify(cleanBlocks);
            const contentField = document.getElementById('content-json');
            
            if (contentField) {
                contentField.value = json;
                console.log('JSON guardado en campo:', json); // Debug
                return true;
            } else {
                console.error('Campo content-json no encontrado');
                return false;
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

        // Actualizar vista previa
        function updatePreview() {
            const previewContainer = document.getElementById('preview');
            previewContainer.innerHTML = '';
            
            contentBlocks.forEach(block => {
                let element;
                
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
                
                previewContainer.appendChild(element);
            });
        }
        
        function updateImageOrder() {
            const container = document.getElementById('images-container');
            if (!container) {
                console.log('No hay container de imágenes'); // Debug
                return;
            }
            
            const images = container.querySelectorAll('[data-id]');
            const order = Array.from(images).map(img => img.dataset.id);
            
            // Solo actualizar el campo hidden, sin hacer petición AJAX
            const orderField = document.getElementById('image-order');
            if (orderField) {
                orderField.value = order.join(',');
                console.log('Orden de imágenes actualizado:', order.join(',')); // Debug
                return true;
            } else {
                console.log('No se encontró campo image-order'); // Debug
                return false;
            }
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
