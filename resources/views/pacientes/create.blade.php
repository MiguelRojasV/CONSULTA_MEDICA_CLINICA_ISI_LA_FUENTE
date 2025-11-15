@extends('layouts.app')

@section('title', 'Nuevo Paciente - Clínica ISI La Fuente')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Registrar Nuevo Paciente</h1>
        <a href="{{ route('pacientes.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Volver a la lista
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('pacientes.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="ci" class="block text-sm font-medium text-gray-700 mb-2">
                    Cédula de Identidad *
                </label>
                <input type="text" 
                       name="ci" 
                       id="ci" 
                       value="{{ old('ci') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
                @error('ci')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre Completo *
                </label>
                <input type="text" 
                       name="nombre" 
                       id="nombre" 
                       value="{{ old('nombre') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
                @error('nombre')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="edad" class="block text-sm font-medium text-gray-700 mb-2">
                    Edad *
                </label>
                <input type="number" 
                       name="edad" 
                       id="edad" 
                       value="{{ old('edad') }}"
                       min="0" 
                       max="150"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
                @error('edad')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="antecedentes" class="block text-sm font-medium text-gray-700 mb-2">
                    Antecedentes Médicos
                </label>
                <textarea name="antecedentes" 
                          id="antecedentes" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('antecedentes') }}</textarea>
                @error('antecedentes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="alergias" class="block text-sm font-medium text-gray-700 mb-2">
                    Alergias
                </label>
                <textarea name="alergias" 
                          id="alergias" 
                          rows="2"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('alergias') }}</textarea>
                @error('alergias')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="contacto_emergencia" class="block text-sm font-medium text-gray-700 mb-2">
                    Contacto de Emergencia
                </label>
                <input type="text" 
                       name="contacto_emergencia" 
                       id="contacto_emergencia" 
                       value="{{ old('contacto_emergencia') }}"
                       placeholder="Nombre y teléfono"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('contacto_emergencia')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('pacientes.index') }}" 
                   class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Paciente
                </button>
            </div>
        </form>
    </div>
</div>