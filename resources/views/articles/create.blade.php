<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Artículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="title" :value="__('Título')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="6" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            <p class="text-sm text-gray-500 mt-1">La descripción soporta emojis 😊</p>
                        </div>

                        <div>
                            <x-input-label for="images" :value="__('Imágenes')" />
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Seleccionar imágenes</span>
                                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" required>
                                        </label>
                                        <p class="pl-1">o arrastrar y soltar</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                                </div>
                            </div>
                            <div id="image-preview" class="mt-2 grid grid-cols-4 gap-4"></div>
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="datasheet" :value="__('Ficha Técnica')" />
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m0-4c0 4.418-7.163 8-16 8S8 28.418 8 24m32 10v6m-16 6c0-4.418 7.163-8 16-8 .553 0 1.099.018 1.64.053" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="datasheet" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Seleccionar archivo</span>
                                            <input id="datasheet" name="datasheet" type="file" class="sr-only" accept=".pdf,.doc,.docx,image/*">
                                        </label>
                                        <p class="pl-1">o arrastrar y soltar</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, IMG hasta 2MB</p>
                                </div>
                            </div>
                            <div id="datasheet-preview" class="mt-2"></div>
                            <x-input-error :messages="$errors->get('datasheet')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('articles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Guardar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview images before upload
        document.getElementById('images').addEventListener('change', function(event) {
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
        
        // Drag and drop functionality for images
        const imagesDropzone = document.querySelector('label[for="images"]').closest('div');
        
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
            document.getElementById('images').files = files;
            
            // Trigger change event to preview images
            const event = new Event('change');
            document.getElementById('images').dispatchEvent(event);
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
    </script>
</x-app-layout>