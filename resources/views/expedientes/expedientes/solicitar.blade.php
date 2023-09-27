<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
<div class="container mx-auto p-4 grid gap-4">
    <div class="md:col-span-1 md:flex md:flex-col md:justify-center md:items-center">
  
    <h1 class="text-2xl font-bold mb-4 text-center md:text-left">{{$expediente->nombre}}</h1>
    
              <!-- Campo oculto para enviar el ID del cliente -->
              <input type="hidden" name="id_expediente" value="{{ $expediente->id_expediente}}">

        <form id="actividadForm" class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-screen-md px-8 pt-8 pb-8 mb-8" action="{{route('almacenarActividad')}}" method="POST">
          @csrf <!-- Agrega el token CSRF para proteger el formulario -->
          
          <!-- Campo oculto para enviar el ID del cliente -->
          <input type="hidden" name="id_expediente" value="{{ $expediente->id_expediente}}">
    
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
              Número de Expediente: {{$expediente->id_expediente}}
            </label>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
              Cliente propietario: {{$expediente->id_cliente}}
            </label>
          </div>
    
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaDevolucion">
              Fecha de Devolución:
            </label>
            <input type="text" id="fechaDevolucion" name="fecha_devolucion" value="2023-09-26" class="w-full p-2 border border-gray-300 rounded">
          </div>
    
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="motivo">
              Motivo:
            </label>
            <textarea id="motivo" name="motivo" class="w-full p-2 border border-gray-300 rounded"></textarea>
          </div>

        <div class="flex justify-center items-center space-x-4 mt-6">
               <a href="{{route('homeExpedientes')}}" class="bg-blue-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver</a>

            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Confirmar con huella</button>
          </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#fechaDevolucion", {
            enableTime: false,
            dateFormat: "Y-m-d",
            locale: "es",
        });
    });
</script>
</body>
</html>
