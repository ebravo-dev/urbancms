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
                        
                        <!-- Gestión de imágenes -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Imágenes Actuales</h3>
                            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                <p class="text-sm text-blue-800">
                                    <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <strong>Optimización automática:</strong> Las imágenes se convertirán automáticamente a formato WebP para mejor rendimiento web. Tamaño máximo: 1200x800px, calidad: 85%.
                                </p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Imagen 1 -->
                                <div class="space-y-3">
                                    <div class="relative">
                                        @if($property->image1)
                                            <img id="current-image-1" src="{{ asset('storage/' . $property->image1) }}" 
                                                 class="h-32 w-full object-cover rounded-lg border-2 border-gray-200">
                                        @else
                                            <div id="current-image-1" class="h-32 w-full bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                                <span class="text-gray-500 text-sm">Sin imagen</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="image1" class="cursor-pointer inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Elegir archivo
                                        </label>
                                        <input type="file" name="image1" id="image1" class="hidden" accept="image/*,.webp">
                                        <p class="text-xs text-gray-500 mt-1">Imagen 1</p>
                                        <div id="preview-image1" class="mt-2 hidden">
                                            <img id="img-preview-1" src="" class="h-32 w-full object-cover rounded-lg border-2 border-indigo-300" alt="Vista previa imagen 1">
                                            <p class="text-xs text-indigo-600 mt-1">Nueva imagen seleccionada</p>
                                        </div>
                                        <x-input-error :messages="$errors->get('image1')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Imagen 2 -->
                                <div class="space-y-3">
                                    <div class="relative">
                                        @if($property->image2)
                                            <img id="current-image-2" src="{{ asset('storage/' . $property->image2) }}" 
                                                 class="h-32 w-full object-cover rounded-lg border-2 border-gray-200">
                                        @else
                                            <div id="current-image-2" class="h-32 w-full bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                                <span class="text-gray-500 text-sm">Sin imagen</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="image2" class="cursor-pointer inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Elegir archivo
                                        </label>
                                        <input type="file" name="image2" id="image2" class="hidden" accept="image/*,.webp">
                                        <p class="text-xs text-gray-500 mt-1">Imagen 2</p>
                                        <div id="preview-image2" class="mt-2 hidden">
                                            <img id="img-preview-2" src="" class="h-32 w-full object-cover rounded-lg border-2 border-indigo-300" alt="Vista previa imagen 2">
                                            <p class="text-xs text-indigo-600 mt-1">Nueva imagen seleccionada</p>
                                        </div>
                                        <x-input-error :messages="$errors->get('image2')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Imagen 3 -->
                                <div class="space-y-3">
                                    <div class="relative">
                                        @if($property->image3)
                                            <img id="current-image-3" src="{{ asset('storage/' . $property->image3) }}" 
                                                 class="h-32 w-full object-cover rounded-lg border-2 border-gray-200">
                                        @else
                                            <div id="current-image-3" class="h-32 w-full bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                                <span class="text-gray-500 text-sm">Sin imagen</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="image3" class="cursor-pointer inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Elegir archivo
                                        </label>
                                        <input type="file" name="image3" id="image3" class="hidden" accept="image/*,.webp">
                                        <p class="text-xs text-gray-500 mt-1">Imagen 3</p>
                                        <div id="preview-image3" class="mt-2 hidden">
                                            <img id="img-preview-3" src="" class="h-32 w-full object-cover rounded-lg border-2 border-indigo-300" alt="Vista previa imagen 3">
                                            <p class="text-xs text-indigo-600 mt-1">Nueva imagen seleccionada</p>
                                        </div>
                                        <x-input-error :messages="$errors->get('image3')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Imagen 4 -->
                                <div class="space-y-3">
                                    <div class="relative">
                                        @if($property->image4)
                                            <img id="current-image-4" src="{{ asset('storage/' . $property->image4) }}" 
                                                 class="h-32 w-full object-cover rounded-lg border-2 border-gray-200">
                                        @else
                                            <div id="current-image-4" class="h-32 w-full bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                                <span class="text-gray-500 text-sm">Sin imagen</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="image4" class="cursor-pointer inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Elegir archivo
                                        </label>
                                        <input type="file" name="image4" id="image4" class="hidden" accept="image/*,.webp">
                                        <p class="text-xs text-gray-500 mt-1">Imagen 4</p>
                                        <div id="preview-image4" class="mt-2 hidden">
                                            <img id="img-preview-4" src="" class="h-32 w-full object-cover rounded-lg border-2 border-indigo-300" alt="Vista previa imagen 4">
                                            <p class="text-xs text-indigo-600 mt-1">Nueva imagen seleccionada</p>
                                        </div>
                                        <x-input-error :messages="$errors->get('image4')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
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

    <script>
        // Funcionalidad de previsualización mejorada para imágenes
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing image previews...');
            
            function setupImagePreview(inputId, previewId, imgId, currentImageId) {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
                const img = document.getElementById(imgId);
                const currentImage = document.getElementById(currentImageId);
                
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
                                
                                // Opcionalmente, ocultar la imagen actual cuando se selecciona una nueva
                                if (currentImage) {
                                    currentImage.style.opacity = '0.5';
                                }
                            };
                            reader.onerror = function() {
                                console.error('Error reading file for:', inputId);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            console.log('No valid image file selected for:', inputId);
                            img.src = '';
                            preview.classList.add('hidden');
                            
                            // Restaurar opacidad de la imagen actual
                            if (currentImage) {
                                currentImage.style.opacity = '1';
                            }
                        }
                    });
                } else {
                    console.error('Missing elements for:', inputId, {input, preview, img});
                }
            }
            
            // Configurar previsualización para cada imagen
            setupImagePreview('image1', 'preview-image1', 'img-preview-1', 'current-image-1');
            setupImagePreview('image2', 'preview-image2', 'img-preview-2', 'current-image-2');
            setupImagePreview('image3', 'preview-image3', 'img-preview-3', 'current-image-3');
            setupImagePreview('image4', 'preview-image4', 'img-preview-4', 'current-image-4');
            
            console.log('Image preview setup completed');
        });
        
    </script>
</x-app-layout>
