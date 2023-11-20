<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Editar Tomo {{ $expediente->nombre }}</h1>
    
    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-lg px-8 pt-6 pb-8 mb-4" action="{{ route('actualizarExp', $expediente->id_expediente) }}" method="POST">
      @csrf <!-- Agrega el token CSRF para proteger el formulario -->
      @method('PUT') <!-- Utiliza el método PUT para la actualización -->
      <input type="hidden" name="nombreDocumento" value="{{$expediente->nombre}}">
      <!--<div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreDocumento">
          Nombre de Tomo
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombreDocumento" type="text" name="nombreDocumento" value="{{ $expediente->nombre }}" required>
      </div>-->

      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
          Descripción
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="descripcion" type="text" name="descripcion" value="{{ $expediente->descripcion }}" required>
      </div> 
      <!--<div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="folioReal">
          Folio Real
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="folioReal" type="text" name="folioReal" value="{{ $expediente->folio_real }}">
      </div>-->
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="otrosDatos">
          Otros datos
        </label>
        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="otrosDatos" name="otrosDatos">{{ $expediente->otros_datos }}</textarea>
      </div>
      <div class="flex items-center justify-end mt-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" type="submit">
          Guardar
        </button>
        <a href="{{ route('homeExpedientes') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
      </div>
    </form>
  </div>
</body>
</html>
