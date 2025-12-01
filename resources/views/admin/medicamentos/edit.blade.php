{{-- ============================================ --}}
{{-- resources/views/admin/medicamentos/edit.blade.php --}}
{{-- Formulario para Editar Medicamento --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Editar Medicamento')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Editar Medicamento</h1>
    <p class="text-gray-600 mt-2">Modificar información de {{ $medicamento->nombre_completo }}</p>
</div>

<form action="{{ route('admin.medicamentos.update', $medicamento) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    
    {{-- Información Básica --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-pills text-orange-600 mr-2"></i>
            Información Básica del Medicamento
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre Genérico <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre_generico" 
                       value="{{ old('nombre_generico', $medicamento->nombre_generico) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('nombre_generico') border-red-500 @enderror">
                @error('nombre_generico')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre Comercial
                </label>
                <input type="text" name="nombre_comercial" 
                       value="{{ old('nombre_comercial', $medicamento->nombre_comercial) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Medicamento
                </label>
                <input type="text" name="tipo" value="{{ old('tipo', $medicamento->tipo) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Presentación
                </label>
                <input type="text" name="presentacion" 
                       value="{{ old('presentacion', $medicamento->presentacion) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Dosis
                </label>
                <input type="text" name="dosis" value="{{ old('dosis', $medicamento->dosis) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Concentración
                </label>
                <input type="text" name="concentracion" 
                       value="{{ old('concentracion', $medicamento->concentracion) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Vía de Administración
                </label>
                <select name="via_administracion"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">Seleccione...</option>
                    <option value="Oral" {{ old('via_administracion', $medicamento->via_administracion) == 'Oral' ? 'selected' : '' }}>Oral</option>
                    <option value="Intravenosa" {{ old('via_administracion', $medicamento->via_administracion) == 'Intravenosa' ? 'selected' : '' }}>Intravenosa</option>
                    <option value="Intramuscular" {{ old('via_administracion', $medicamento->via_administracion) == 'Intramuscular' ? 'selected' : '' }}>Intramuscular</option>
                    <option value="Tópica" {{ old('via_administracion', $medicamento->via_administracion) == 'Tópica' ? 'selected' : '' }}>Tópica</option>
                    <option value="Subcutánea" {{ old('via_administracion', $medicamento->via_administracion) == 'Subcutánea' ? 'selected' : '' }}>Subcutánea</option>
                    <option value="Inhalatoria" {{ old('via_administracion', $medicamento->via_administracion) == 'Inhalatoria' ? 'selected' : '' }}>Inhalatoria</option>
                    <option value="Oftálmica" {{ old('via_administracion', $medicamento->via_administracion) == 'Oftálmica' ? 'selected' : '' }}>Oftálmica</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Laboratorio
                </label>
                <input type="text" name="laboratorio" 
                       value="{{ old('laboratorio', $medicamento->laboratorio) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
        </div>
    </div>
    
    {{-- Control de Inventario --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-boxes text-blue-600 mr-2"></i>
            Control de Inventario
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Stock Disponible <span class="text-red-500">*</span>
                </label>
                <input type="number" name="disponibilidad" 
                       value="{{ old('disponibilidad', $medicamento->disponibilidad) }}" required
                       min="0" max="999999" step="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('disponibilidad') border-red-500 @enderror">
                @error('disponibilidad')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Stock Mínimo <span class="text-red-500">*</span>
                </label>
                <input type="number" name="stock_minimo" 
                       value="{{ old('stock_minimo', $medicamento->stock_minimo) }}" required
                       min="0" max="999" step="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Precio Unitario (Bs.)
                </label>
                <input type="number" name="precio_unitario" 
                       value="{{ old('precio_unitario', $medicamento->precio_unitario) }}"
                       min="0" max="99999.99" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Caducidad
                </label>
                <input type="date" name="caducidad" 
                       value="{{ old('caducidad', $medicamento->caducidad ? $medicamento->caducidad->format('Y-m-d') : '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Lote
                </label>
                <input type="text" name="lote" value="{{ old('lote', $medicamento->lote) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex items-center mt-6">
                <input type="checkbox" name="requiere_receta" value="1" 
                       {{ old('requiere_receta', $medicamento->requiere_receta) ? 'checked' : '' }}
                       class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                <label class="ml-2 text-sm font-medium text-gray-700">
                    Requiere Receta Médica
                </label>
            </div>
        </div>
    </div>
    
    {{-- Información Adicional --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-info-circle text-purple-600 mr-2"></i>
            Información Adicional
        </h2>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Contraindicaciones
            </label>
            <textarea name="contraindicaciones" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('contraindicaciones', $medicamento->contraindicaciones) }}</textarea>
        </div>
    </div>
    
    {{-- Botones --}}
    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.medicamentos.show', $medicamento) }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-times mr-2"></i>Cancelar
        </a>
        <button type="submit" 
                class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition shadow-lg">
            <i class="fas fa-save mr-2"></i>Guardar Cambios
        </button>
    </div>
</form>
@endsection