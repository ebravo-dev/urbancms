<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Propiedad
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('properties.edit', $property) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Editar
                </a>
                <form action="{{ route('properties.destroy', $property) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 mx-2" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?')">
                        Eliminar
                    </button>
                </form>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <!-- Imágenes -->
                    <div class="mb-6">
                        <div class="grid grid-cols-1 gap-4">
                            @if($property->image1)
                                <div class="relative aspect-video overflow-hidden rounded-lg bg-gray-100">
                                    <img id="main-image" src="{{ asset('storage/' . $property->image1) }}" 
                                        alt="Imagen principal" 
                                        class="h-full w-full object-cover">
                                </div>
                                
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if($property->image1)
                                        <div class="w-24 h-24 cursor-pointer overflow-hidden rounded-lg hover:ring-2 hover:ring-indigo-500" 
                                            onclick="changeMainImage('{{ asset('storage/' . $property->image1) }}')">
                                            <img src="{{ asset('storage/' . $property->image1) }}" 
                                                class="h-full w-full object-cover">
                                        </div>
                                    @endif
                                    
                                    @if($property->image2)
                                        <div class="w-24 h-24 cursor-pointer overflow-hidden rounded-lg hover:ring-2 hover:ring-indigo-500" 
                                            onclick="changeMainImage('{{ asset('storage/' . $property->image2) }}')">
                                            <img src="{{ asset('storage/' . $property->image2) }}" 
                                                class="h-full w-full object-cover">
                                        </div>
                                    @endif
                                    
                                    @if($property->image3)
                                        <div class="w-24 h-24 cursor-pointer overflow-hidden rounded-lg hover:ring-2 hover:ring-indigo-500" 
                                            onclick="changeMainImage('{{ asset('storage/' . $property->image3) }}')">
                                            <img src="{{ asset('storage/' . $property->image3) }}" 
                                                class="h-full w-full object-cover">
                                        </div>
                                    @endif
                                    
                                    @if($property->image4)
                                        <div class="w-24 h-24 cursor-pointer overflow-hidden rounded-lg hover:ring-2 hover:ring-indigo-500" 
                                            onclick="changeMainImage('{{ asset('storage/' . $property->image4) }}')">
                                            <img src="{{ asset('storage/' . $property->image4) }}" 
                                                class="h-full w-full object-cover">
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="prose max-w-none">
                        <!-- Tipo de operación -->
                        <div class="mb-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium {{ $property->is_for_sale ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $property->is_for_sale ? 'Venta' : 'Renta' }}
                            </span>
                        </div>
                        
                        <!-- Ubicación -->
                        @if($property->location_line1 || $property->location_line2 || $property->location_line3)
                            <div class="mb-6">
                                <h2 class="text-xl font-semibold mb-2">Ubicación</h2>
                                <div class="space-y-1">
                                    @if($property->location_line1)
                                        <p>{{ $property->location_line1 }}</p>
                                    @endif
                                    @if($property->location_line2)
                                        <p>{{ $property->location_line2 }}</p>
                                    @endif
                                    @if($property->location_line3)
                                        <p>{{ $property->location_line3 }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Google Maps -->
                        @if($property->google_maps_url)
                            <div class="mb-6">
                                <h2 class="text-xl font-semibold mb-2">Ubicación en Mapa</h2>
                                <div>
                                    <a href="{{ $property->google_maps_url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Ver ubicación en Google Maps
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Características -->
                        @php
                            $features = [
                                $property->feature1,
                                $property->feature2,
                                $property->feature3,
                                $property->feature4,
                                $property->feature5,
                                $property->feature6,
                                $property->feature7,
                                $property->feature8
                            ];
                            $hasFeatures = array_filter($features) !== [];
                        @endphp
                        
                        @if($hasFeatures)
                            <div class="mb-6">
                                <h2 class="text-xl font-semibold mb-2">Características</h2>
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($features as $feature)
                                        @if($feature)
                                            <li class="flex items-center">
                                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                {{ $feature }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Inversión -->
                        @if($property->investment)
                            <div class="mb-6">
                                <h2 class="text-xl font-semibold mb-2">Inversión</h2>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($property->investment, 2) }} MXN</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(src) {
            document.getElementById('main-image').src = src;
        }
    </script>
</x-app-layout>
