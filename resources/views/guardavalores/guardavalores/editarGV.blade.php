<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Editar {{ $gv->tipo_gv }}</h1>
    
    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-2xl px-8 pt-6 pb-8 mb-4" action="{{ route('actualizarGV', $gv->id_documento) }}" method="POST">
      @csrf <!-- Agrega el token CSRF para proteger el formulario -->
      @method('PUT') <!-- Utiliza el método PUT para la actualización -->

      <div class="grid grid-cols-2 gap-4">
        @php $elements = ['nombre', 'numero_contrato', 'fecha_acta', 'numero_pagare', 'fechaActa', 'descripcion', 'folio_real', 'otros_datos', 'funcionario', 'monto', 'cantidad', 'kilos', 'numeroCheque', 'concepto']; @endphp
        
        @foreach($elements as $element)
          @if (!empty($gv->$element))
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="{{ $element }}">
                {{ ucwords(str_replace('_', ' ', $element)) }}
              </label>
              <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="{{ $element }}" type="text" name="{{ $element }}" value="{{ $gv->$element }}" required>
            </div>
          @endif
        @endforeach
      </div>

      <br>

      <div class="flex items-center justify-center mt-4"> <!-- Utiliza justify-center para centrar los botones -->
        <button class="bg-blue-500 hover-bg-blue-700 text-white font-bold py-2 px-4 rounded focus-outline-none focus-shadow-outline mr-2" type="submit">
          Guardar
        </button>
        <a href="{{ route('homeAdminGuardavalores') }}" class="bg-red-500 hover-bg-red-700 text-white font-bold py-2 px-4 rounded focus-outline-none focus-shadow-outline">Cancelar</a>
      </div>

    </form>
  </div>
</body>
</html>
