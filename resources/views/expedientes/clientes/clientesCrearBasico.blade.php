<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Cliente</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Registar Expediente Cliente BASICO</h1>
    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-lg px-8 pt-6 pb-8 mb-4" action="{{ route('storeUsuarioClienteBasico') }}" method="POST">
    @csrf <!-- Agregar el token CSRF aquí -->
    <input type="hidden" name="id_usuario" value="{{$id_usuario}}">

    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreDocumento">
          Nombre Completo de Cliente
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" type="text" name="nombre" placeholder="Nombre de Cliente" required>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="numeroExpediente">
          Número de expediente
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numeroExpediente" type="text" name="numeroExpediente" placeholder="Número de Expediente" required>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="idClienteGiro">
          ID Cliente Giro
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="idClienteGiro" type="text" name="idClienteGiro" placeholder="ID Cliente Giro" required>
      </div>
      
      <div class="flex items-center justify-end mt-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" type="submit">
          Guardar
        </button>
        <a href="{{route('homeClientesUsuario',$id_usuario)}}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
      </div>

    </form>
  </div>
</body>
</html>
