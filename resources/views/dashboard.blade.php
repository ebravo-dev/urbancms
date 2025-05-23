<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Gestión de Propiedades -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">{{ __('Gestión de Propiedades') }}</h3>

                        <div class="space-y-2">
                            <a href="{{ route('properties.index') }}" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Administrar Propiedades') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Ver, crear, editar y eliminar propiedades</div>
                            </a>
                            
                            <a href="{{ route('properties.create') }}" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Nueva Propiedad') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Agregar una nueva propiedad al catálogo</div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Gestión de Blog -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">{{ __('Gestión de Blog') }}</h3>
                        
                        <div class="space-y-2">
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Administrar Entradas') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Ver, crear, editar y eliminar entradas del blog</div>
                            </a>
                            
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Nueva Entrada') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Crear una nueva entrada para el blog</div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Enlaces Rápidos -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">{{ __('Enlaces Rápidos') }}</h3>
                        
                        <div class="space-y-2">
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Mi Perfil') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Gestionar información de la cuenta</div>
                            </a>
                            
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Ver Sitio Público') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Visualizar el sitio como lo ven los usuarios</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-center text-sm text-gray-500 py-4">
                Creado por CampusTD 2025
            </div>
        </div>
    </div>
</x-app-layout>
