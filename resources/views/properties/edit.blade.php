<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Propiedad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Tipo de operación (Venta o Renta) -->
                        <div>
                            <x-input-label :value="__('VENTA O RENTA')" />
                            <div class="mt-2">
                                <div class="flex items-center">
                                    <input id="is_for_sale_1" name="is_for_sale" type="radio" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" {{ old('is_for_sale', $property->is_for_sale) == '1' ? 'checked' : '' }}>
                                    <label for="is_for_sale_1" class="ml-3 block text-sm font-medium text-gray-700">Venta</label>
                                </div>
                                <div class="flex items-center mt-2">
                                    <input id="is_for_sale_0" name="is_for_sale" type="radio" value="0" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" {{ old('is_for_sale', $property->is_for_sale) == '0' ? 'checked' : '' }}>
                                    <label for="is_for_sale_0" class="ml-3 block text-sm font-medium text-gray-700">Renta</label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('is_for_sale')" class="mt-2" />
                        </div>

                        <!-- Ubicación (3 líneas) -->
                        <div>
                            <x-input-label for="location_line1" :value="__('UBICACION FILA 1')" />
                            <x-text-input id="location_line1" name="location_line1" type="text" class="mt-1 block w-full" :value="old('location_line1', $property->location_line1)" />
                            <x-input-error :messages="$errors->get('location_line1')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="location_line2" :value="__('UBICACION FILA 2')" />
                            <x-text-input id="location_line2" name="location_line2" type="text" class="mt-1 block w-full" :value="old('location_line2', $property->location_line2)" />
                            <x-input-error :messages="$errors->get('location_line2')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="location_line3" :value="__('UBICACION FILA 3')" />
                            <x-text-input id="location_line3" name="location_line3" type="text" class="mt-1 block w-full" :value="old('location_line3', $property->location_line3)" />
                            <x-input-error :messages="$errors->get('location_line3')" class="mt-2" />
                        </div>

                        <!-- Google Maps URL -->
                        <div>
                            <x-input-label for="google_maps_url" :value="__('GOOGLE MAPS')" />
                            <div class="flex space-x-2">
                                <x-text-input id="google_maps_url" name="google_maps_url" type="url" class="mt-1 block w-full" :value="old('google_maps_url', $property->google_maps_url)" placeholder="https://www.google.com/maps?q=ubicacion+de+la+propiedad" />
                                <a id="open_maps_btn" href="https://www.google.com/maps" target="_blank" class="mt-1 px-4 py-2 bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Abrir Maps
                                </a>
                            </div>
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const mapUrlField = document.getElementById('google_maps_url');
                                    const openMapsBtn = document.getElementById('open_maps_btn');
                                    
                                    // Actualiza el enlace cuando cambia el campo de URL
                                    mapUrlField.addEventListener('input', updateMapLink);
                                    mapUrlField.addEventListener('change', updateMapLink);
                                    
                                    // Actualiza el enlace al cargar la página
                                    updateMapLink();
                                    
                                    function updateMapLink() {
                                        if (mapUrlField.value) {
                                            openMapsBtn.href = mapUrlField.value;
                                        } else {
                                            openMapsBtn.href = 'https://www.google.com/maps';
                                        }
                                    }
                                });
                            </script>
                            <x-input-error :messages="$errors->get('google_maps_url')" class="mt-2" />
                            <p class="text-sm text-gray-500 mt-1">Instrucciones:</p>
                            <ol class="list-decimal list-inside text-sm text-gray-500 ml-2 space-y-1">
                                <li>Ve a <a href="https://www.google.com/maps" target="_blank" class="text-indigo-600 hover:text-indigo-800">Google Maps</a> y busca la ubicación de la propiedad</li>
                                <li>Haz clic derecho en el punto exacto y selecciona "¿Qué hay aquí?" o "Compartir"</li>
                                <li>Copia la URL desde tu navegador y pégala en este campo</li>
                            </ol>
                        </div>

                        <!-- Características (8 campos) -->
                        <div>
                            <x-input-label :value="__('CARACTERÍSTICAS')" />
                            <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-text-input id="feature1" name="feature1" type="text" class="mt-1 block w-full" :value="old('feature1', $property->feature1)" placeholder="Característica 1" />
                                    <x-input-error :messages="$errors->get('feature1')" class="mt-2" />
                                </div>
                                <div>
                                    <x-text-input id="feature2" name="feature2" type="text" class="mt-1 block w-full" :value="old('feature2', $property->feature2)" placeholder="Característica 2" />
                                    <x-input-error :messages="$errors->get('feature2')" class="mt-2" />
                                </div>
                                <div>
                                    <x-text-input id="feature3" name="feature3" type="text" class="mt-1 block w-full" :value="old('feature3', $property->feature3)" placeholder="Característica 3" />
                                    <x-input-error :messages="$errors->get('feature3')" class="mt-2" />
                                </div>
                                <div>
                                    <x-text-input id="feature4" name="feature4" type="text" class="mt-1 block w-full" :value="old('feature4', $property->feature4)" placeholder="Característica 4" />
                                    <x-input-error :messages="$errors->get('feature4')" class="mt-2" />
                                </div>
                                <div>
                                    <x-text-input id="feature5" name="feature5" type="text" class="mt-1 block w-full" :value="old('feature5', $property->feature5)" placeholder="Característica 5" />
                                    <x-input-error :messages="$errors->get('feature5')" class="mt-2" />
                                </div>
                                <div>
                                    <x-text-input id="feature6" name="feature6" type="text" class="mt-1 block w-full" :value="old('feature6', $property->feature6)" placeholder="Característica 6" />
                                    <x-input-error :messages="$errors->get('feature6')" class="mt-2" />
                                </div>
                                <div>
                                    <x-text-input id="feature7" name="feature7" type="text" class="mt-1 block w-full" :value="old('feature7', $property->feature7)" placeholder="Característica 7" />
                                    <x-input-error :messages="$errors->get('feature7')" class="mt-2" />
                                </div>
                                <div>
                                    <x-text-input id="feature8" name="feature8" type="text" class="mt-1 block w-full" :value="old('feature8', $property->feature8)" placeholder="Característica 8" />
                                    <x-input-error :messages="$errors->get('feature8')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Inversión (Precio) -->
                        <div>
                            <x-input-label for="investment" :value="__('INVERSIÓN')" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="investment" id="investment" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" value="{{ old('investment', $property->investment) }}">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">MXN</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('investment')" class="mt-2" />
                        </div>
                        
                        <!-- Imágenes actuales y eliminación -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Imágenes Actuales</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-3">
                                @if($property->image1)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $property->image1) }}" 
                                             class="h-32 w-full object-cover rounded-lg">
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="delete_image1" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-red-600 font-medium">Eliminar imagen 1</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($property->image2)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $property->image2) }}" 
                                             class="h-32 w-full object-cover rounded-lg">
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="delete_image2" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-red-600 font-medium">Eliminar imagen 2</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($property->image3)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $property->image3) }}" 
                                             class="h-32 w-full object-cover rounded-lg">
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="delete_image3" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-red-600 font-medium">Eliminar imagen 3</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($property->image4)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $property->image4) }}" 
                                             class="h-32 w-full object-cover rounded-lg">
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="delete_image4" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-red-600 font-medium">Eliminar imagen 4</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Nuevas imágenes -->
                        <div>
                            <x-input-label for="image1" :value="__('IMAGEN 1')" />
                            <input type="file" name="image1" id="image1" class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100" accept="image/*">
                            <p class="text-xs text-gray-500 mt-1">Cargar desde PC</p>
                            <div id="preview-image1" class="mt-2 hidden">
                                <img id="img-preview-1" src="" class="h-32 w-auto object-cover rounded-lg border border-gray-300" alt="Vista previa imagen 1">
                            </div>
                            <x-input-error :messages="$errors->get('image1')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="image2" :value="__('IMAGEN 2')" />
                            <input type="file" name="image2" id="image2" class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100" accept="image/*">
                            <p class="text-xs text-gray-500 mt-1">Cargar desde PC</p>
                            <div id="preview-image2" class="mt-2 hidden">
                                <img id="img-preview-2" src="" class="h-32 w-auto object-cover rounded-lg border border-gray-300" alt="Vista previa imagen 2">
                            </div>
                            <x-input-error :messages="$errors->get('image2')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="image3" :value="__('IMAGEN 3')" />
                            <input type="file" name="image3" id="image3" class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100" accept="image/*">
                            <p class="text-xs text-gray-500 mt-1">Cargar desde PC</p>
                            <div id="preview-image3" class="mt-2 hidden">
                                <img id="img-preview-3" src="" class="h-32 w-auto object-cover rounded-lg border border-gray-300" alt="Vista previa imagen 3">
                            </div>
                            <x-input-error :messages="$errors->get('image3')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="image4" :value="__('IMAGEN 4')" />
                            <input type="file" name="image4" id="image4" class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100" accept="image/*">
                            <p class="text-xs text-gray-500 mt-1">Cargar desde PC</p>
                            <div id="preview-image4" class="mt-2 hidden">
                                <img id="img-preview-4" src="" class="h-32 w-auto object-cover rounded-lg border border-gray-300" alt="Vista previa imagen 4">
                            </div>
                            <x-input-error :messages="$errors->get('image4')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Actualizar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Sortable.js para reordenar imágenes
        const sortableList = document.getElementById('sortable-images');
        if (sortableList) {
            const sortable = new Sortable(sortableList, {
                animation: 150,
                ghostClass: 'bg-indigo-100',
                onEnd: function(evt) {
                    // Enviar el nuevo orden al servidor
                    const imageIds = Array.from(sortableList.children)
                        .map(item => item.getAttribute('data-id'));
                    
                    fetch('{{ route("properties.reorder-images", $property) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ images: imageIds })
                    })
                    .then(response => response.json())
                    .then data => {
                        if (data.success) {
                            // Mostrar mensaje de éxito
                            const successMessage = document.createElement('div');
                            successMessage.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
                            successMessage.innerHTML = '<span class="block sm:inline">Orden actualizado correctamente</span>';
                            sortableList.parentNode.insertBefore(successMessage, sortableList);
                            
                            // Ocultar el mensaje después de 3 segundos
                            setTimeout(() => {
                                successMessage.remove();
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }

        // Handle deletion indicators for existing images
        const deleteCheckboxes = document.querySelectorAll('input[name="delete_images[]"]');
        deleteCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const imageId = this.value;
                const indicator = document.getElementById(`delete_indicator_${imageId}`);
                
                if (this.checked) {
                    indicator.classList.remove('hidden');
                } else {
                    indicator.classList.add('hidden');
                }
            });
        });
        
        // Preview new images before upload and validate max 4 images
        document.getElementById('new_images').addEventListener('change', function(event) {
            // Count existing images (not marked for deletion)
            const existingImages = document.querySelectorAll('#sortable-images > div').length;
            const markedForDeletion = document.querySelectorAll('input[name="delete_images[]"]:checked').length;
            const remainingImages = existingImages - markedForDeletion;
            
            if (event.target.files.length + remainingImages > 4) {
                alert(`Solo puedes tener un máximo de 4 imágenes. Puedes añadir ${4 - remainingImages} imágenes más.`);
                event.target.value = '';
                return;
            }
            
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            
            for (const file of event.target.files) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-32 w-full object-cover rounded';
                    
                    div.appendChild(img);
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Preview datasheet before upload
        document.getElementById('datasheet').addEventListener('change', function(event) {
            const preview = document.getElementById('datasheet-preview');
            preview.innerHTML = '';
            
            if (event.target.files.length > 0) {
                const file = event.target.files[0];
                const fileName = file.name;
                
                const div = document.createElement('div');
                div.className = 'mt-2 flex items-center';
                
                const iconClass = fileName.match(/\.(pdf)$/i) ? 'text-red-500' : 
                                 fileName.match(/\.(doc|docx)$/i) ? 'text-blue-500' : 'text-green-500';
                
                div.innerHTML = `
                    <svg class="h-5 w-5 ${iconClass}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-2 text-sm text-gray-900">${fileName}</span>
                `;
                
                preview.appendChild(div);
            }
        });
        
        // Drag and drop functionality for new images
        const imagesDropzone = document.querySelector('label[for="new_images"]').closest('div');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            imagesDropzone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            imagesDropzone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            imagesDropzone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            imagesDropzone.closest('.border-dashed').classList.add('border-indigo-500', 'bg-indigo-50');
            imagesDropzone.closest('.border-dashed').classList.remove('border-gray-300');
        }
        
        function unhighlight() {
            imagesDropzone.closest('.border-dashed').classList.remove('border-indigo-500', 'bg-indigo-50');
            imagesDropzone.closest('.border-dashed').classList.add('border-gray-300');
        }
        
        imagesDropzone.addEventListener('drop', handleImagesDrop, false);
        
        function handleImagesDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            // Count existing images (not marked for deletion)
            const existingImages = document.querySelectorAll('#sortable-images > div').length;
            const markedForDeletion = document.querySelectorAll('input[name="delete_images[]"]:checked').length;
            const remainingImages = existingImages - markedForDeletion;
            
            if (files.length + remainingImages > 4) {
                alert(`Solo puedes tener un máximo de 4 imágenes. Puedes añadir ${4 - remainingImages} imágenes más.`);
                return;
            }
            
            document.getElementById('new_images').files = files;
            
            // Trigger change event to preview images
            const event = new Event('change');
            document.getElementById('new_images').dispatchEvent(event);
        }
        
        // Drag and drop functionality for datasheet
        const datasheetDropzone = document.querySelector('label[for="datasheet"]').closest('div');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            datasheetDropzone.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            datasheetDropzone.addEventListener(eventName, highlightDatasheet, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            datasheetDropzone.addEventListener(eventName, unhighlightDatasheet, false);
        });
        
        function highlightDatasheet() {
            datasheetDropzone.closest('.border-dashed').classList.add('border-indigo-500', 'bg-indigo-50');
            datasheetDropzone.closest('.border-dashed').classList.remove('border-gray-300');
        }
        
        function unhighlightDatasheet() {
            datasheetDropzone.closest('.border-dashed').classList.remove('border-indigo-500', 'bg-indigo-50');
            datasheetDropzone.closest('.border-dashed').classList.add('border-gray-300');
        }
        
        datasheetDropzone.addEventListener('drop', handleDatasheetDrop, false);
        
        function handleDatasheetDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                const fileList = new DataTransfer();
                fileList.items.add(files[0]);
                document.getElementById('datasheet').files = fileList.files;
                
                // Trigger change event to preview datasheet
                const event = new Event('change');
                document.getElementById('datasheet').dispatchEvent(event);
            }
        }
        
        // Funcionalidad de previsualización para imágenes individuales
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing image previews...');
            
            function setupImagePreview(inputId, previewId, imgId) {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
                const img = document.getElementById(imgId);
                
                console.log('Setting up preview for:', inputId, input, preview, img);
                
                if (input && preview && img) {
                    input.addEventListener('change', function(event) {
                        console.log('File changed for:', inputId);
                        const file = event.target.files[0];
                        if (file && file.type.startsWith('image/')) {
                            console.log('Valid image file selected:', file.name, file.type);
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                console.log('File read successful for:', inputId);
                                img.src = e.target.result;
                                preview.classList.remove('hidden');
                            };
                            reader.onerror = function() {
                                console.error('Error reading file for:', inputId);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            console.log('No valid image file selected for:', inputId);
                            img.src = '';
                            preview.classList.add('hidden');
                        }
                    });
                } else {
                    console.error('Missing elements for:', inputId, {input, preview, img});
                }
            }
            
            // Configurar previsualización para cada imagen
            setupImagePreview('image1', 'preview-image1', 'img-preview-1');
            setupImagePreview('image2', 'preview-image2', 'img-preview-2');
            setupImagePreview('image3', 'preview-image3', 'img-preview-3');
            setupImagePreview('image4', 'preview-image4', 'img-preview-4');
            
            console.log('Image preview setup completed');
        });
        
    </script>
</x-app-layout>
