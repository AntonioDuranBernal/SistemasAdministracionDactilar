<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Editar Guardavalor</h1>
    
    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-2xl px-8 pt-6 pb-8 mb-4" action="{{ route('actualizarGV', $gv->id_documento) }}" method="POST">
      @csrf <!-- Agrega el token CSRF para proteger el formulario -->
      @method('PUT') <!-- Utiliza el método PUT para la actualización -->

      <div class="grid grid-cols-2 gap-4">
        @if (!empty($gv->nombre))
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
            Nombre
          </label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" type="text" name="nombre" value="{{ $gv->nombre }}" required>
        </div>
        @endif

        @if (!empty($gv->numero_contrato))
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="numero_contrato">
            Número de Contrato
          </label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numero_contrato" type="text" name="numero_contrato" value="{{ $gv->numero_contrato }}" required>
        </div>
        @endif

        @if (!empty($gv->numero_pagare))
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="numero_pagare">
            Número Pagare
          </label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numero_pagare" type="text" name="numero_pagare" value="{{ $gv->numero_pagare }}">
        </div>
        @endif

        @if (!empty($gv->descripcion))
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
            Descripción
          </label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="descripcion" type="text" name="descripcion" value="{{ $gv->descripcion }}" required>
        </div>
        @endif

        @if (!empty($gv->folio_real))
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="folio_real">
            Folio Real
          </label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="folio_real" type="text" name="folio_real" value="{{ $gv->folio_real }}">
        </div>
        @endif

        @if (!empty($gv->otros_datos))
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="otros_datos">
            Otros datos
          </label>
          <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="otros_datos" name="otros_datos">{{ $gv->otros_datos }}</textarea>
        </div>
        @endif

        @if (!empty($gv->funcionario))
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="funcionario">
            Funcionario
          </label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="funcionario" type="text" name="funcionario" value="{{ $gv->funcionario }}">
        </div>
        @endif

        @if (!empty($gv->monto))
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="monto">
            Monto
          </label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="monto" type="text" name="monto" value="{{ $gv->monto }}">
        </div>
        @endif

        <div class="mb-4 col-span-2">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="tipo_credito">
            Tipo de Crédito
          </label>
          <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tipo_credito" name="tipo_credito">
              <option value="Credito" {{ $gv->tipo_credito == 'Credito' ? 'selected' : '' }}>Credito</option>
              <option value="Microcredito" {{ $gv->tipo_credito == 'Microcredito' ? 'selected' : '' }}>Microcredito</option>
          </select>
        </div>
      </div>

      <div class="flex items-center justify-end mt-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" type="submit">
          Guardar
        </button>
        <a href="{{ route('homeAdminGuardavalores') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
      </div>
    </form>
  </div>
</body>
</html>
