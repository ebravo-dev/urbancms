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
                                            <td class="py-2 px-4 border-b">{{ $property->id }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium {{ $property->is_for_sale ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $property->is_for_sale ? 'Venta' : 'Renta' }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                {{ $property->location_line1 ?? 'N/A' }}
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                @if($property->investment)
                                                    <span class="font-medium text-green-700">${{ number_format($property->investment, 2) }}</span>
                                                @else
                                                    <span class="text-gray-400">No especificada</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                @if($property->image1)
                                                    <img src="{{ asset('storage/' . $property->image1) }}" 
                                                         alt="Imagen principal" 
                                                         class="h-16 w-auto object-cover">
                                                @else
                                                    <span class="text-gray-400">Sin imagen</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('properties.edit', $property) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        Editar
                                                    </a>
                                                    <form action="{{ route('properties.destroy', $property) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
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
</x-app-layout>
