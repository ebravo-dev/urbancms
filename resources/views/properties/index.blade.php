<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Propiedades') }}
            </h2>
            <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nueva Propiedad
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($properties->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">ID</th>
                                        <th class="py-2 px-4 border-b text-left">Tipo</th>
                                        <th class="py-2 px-4 border-b text-left">Ubicación</th>
                                        <th class="py-2 px-4 border-b text-left">Inversión</th>
                                        <th class="py-2 px-4 border-b text-left">Imagen</th>
                                        <th class="py-2 px-4 border-b text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($properties as $property)
                                        <tr>
                                            <td class="py-2 px-4 border-b cursor-pointer" onclick="window.location='{{ route('properties.show', $property) }}'">{{ $property->id }}</td>
                                            <td class="py-2 px-4 border-b cursor-pointer" onclick="window.location='{{ route('properties.show', $property) }}'">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium {{ $property->is_for_sale ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $property->is_for_sale ? 'Venta' : 'Renta' }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b cursor-pointer" onclick="window.location='{{ route('properties.show', $property) }}'">
                                                {{ $property->location_line1 ?? 'N/A' }}
                                            </td>
                                            <td class="py-2 px-4 border-b cursor-pointer" onclick="window.location='{{ route('properties.show', $property) }}'">
                                                @if($property->investment)
                                                    <span class="font-medium text-green-700">${{ number_format($property->investment, 2) }}</span>
                                                @else
                                                    <span class="text-gray-400">No especificada</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b cursor-pointer" onclick="window.location='{{ route('properties.show', $property) }}'">
                                                @if($property->image1)
                                                    <img src="{{ asset('storage/' . $property->image1) }}" 
                                                         alt="Imagen principal" 
                                                         class="h-16 w-auto object-cover">
                                                @else
                                                    <span class="text-gray-400">Sin imagen</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <a href="{{ route('properties.show', $property) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    VER
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $properties->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No hay propiedades disponibles.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .clickable-row {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .clickable-row:hover td {
            background-color: rgba(59, 130, 246, 0.1);
        }
        .clickable-row:hover {
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.3);
        }
        /* Mantener el cursor normal en botones y enlaces */
        .clickable-row td:last-child a {
            cursor: pointer;
        }
    </style>

    <script>
        // Manejo de filas clickeables
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.classList.add('clickable-row');
                
                // Evitar que el evento de click en los botones propague al tr
                const actionCell = row.querySelector('td:last-child');
                if (actionCell) {
                    actionCell.addEventListener('click', function(e) {
                        if (e.target.tagName === 'A' || e.target.closest('a')) {
                            e.stopPropagation();
                        }
                    });
                }
                
                // Añadir evento click a toda la fila
                row.addEventListener('click', function(e) {
                    // No hacer nada si hacemos clic en un enlace o botón
                    if (e.target.tagName === 'A' || e.target.closest('a') || 
                        e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                        return;
                    }
                    
                    // Obtener el ID de la propiedad
                    const id = row.querySelector('td:first-child').textContent.trim();
                    // Redirigir a la página de detalle
                    window.location.href = '/properties/' + id;
                });
            });
        });
    </script>
</x-app-layout>
