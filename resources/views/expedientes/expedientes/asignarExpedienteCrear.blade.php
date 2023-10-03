<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Asignar Expediente</h1>
    
    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-lg px-8 pt-6 pb-8 mb-4" action="{{route('expedienteGuardar')}}" method="POST">
      @csrf <!-- Agrega el token CSRF para proteger el formulario -->
      
      <!-- Campo oculto para enviar el ID del cliente -->
      <input type="hidden" name="id_ciente" value="{{ $cliente->id_cliente}}">
      
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
          {{$cliente->nombre}} ({{$cliente->id_cliente}})
        </label>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreDocumento">
          Nombre de Expediente
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombreDocumento" type="text" name="nombreExpediente" placeholder="Nombre de Expediente" required>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
          Descripción
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="descripcion" type="text" name="descripcion" placeholder="Descripción" required>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="folioReal">
          Folio Real
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="folioReal" type="text" name="folioReal" placeholder="Folio Real">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="otrosDatos">
          Otros datos
        </label>
        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="otrosDatos" name="otrosDatos" placeholder="Otros datos"></textarea>
      </div>
      <div class="flex items-center justify-end mt-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" type="submit">
          Guardar
        </button>
        <a href="{{route('homeClientesSuper')}}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
      </div>
    </form>
  </div>
</body>
</html>
