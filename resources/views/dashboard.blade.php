<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Gestión de Libros -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">{{ __('Gestión de Libros') }}</h3>

                        <div class="space-y-2">
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Administrar Catálogo') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Ver, crear, editar y eliminar libros</div>
                            </a>
                            
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Nuevo Libro') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Agregar una nueva publicación al catálogo</div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Gestión de Usuarios -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">{{ __('Gestión de Usuarios') }}</h3>
                        
                        <div class="space-y-2">
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Administrar Usuarios') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Ver, crear, editar y eliminar usuarios del sistema</div>
                            </a>
                            
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Nuevo Usuario') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Agregar un nuevo usuario al sistema</div>
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
                                <div class="font-medium">{{ __('Ver Catálogo Público') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Visualizar el catálogo como lo ven los usuarios</div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">{{ __('Estadísticas') }}</h3>
                        
                        <div class="space-y-2">
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Reportes') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Ver estadísticas y reportes del sistema</div>
                            </a>
                            
                            <a href="#" class="block p-4 border border-gray-200 rounded hover:bg-gray-50 transition duration-200">
                                <div class="font-medium">{{ __('Actividad Reciente') }}</div>
                                <div class="text-sm text-gray-500 mt-1">Revisar la actividad reciente del sistema</div>
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
