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

        <form id="actividadForm" class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-screen-md px-8 pt-8 pb-8 mb-8" action="{{route('almacenarActividadUsuarioBasico')}}" method="POST">
          @csrf <!-- Agrega el token CSRF para proteger el formulario -->
          
          <!-- Campo oculto para enviar el ID del cliente -->
          <input type="hidden" name="id_expediente" value="{{ $expediente->id_expediente}}">
          <input type="hidden" name="id_usuario" value="{{ $id_usuario}}">
    
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
              Número de Tomo: {{$expediente->id_expediente}}
            </label>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
              Expediente: {{$expediente->id_cliente}}
            </label>
          </div>
    
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaDevolucion">
                Fecha de Devolución:
            </label>
            <input type="date" id="fechaDevolucion" name="fecha_devolucion" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
    
        <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="motivo">
        Motivo (máximo 250 caracteres):
    </label>
    <textarea id="motivo" name="motivo" placeholder="Ingresa el motivo de tu solicitud" class="w-full p-2 border border-gray-300 rounded" required maxlength="250"></textarea>
</div>

        <div class="flex justify-center items-center space-x-4 mt-6">
               <a href="{{ route('expedientesBasico',$id_usuario) }}" class="bg-blue-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver</a>

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

    // Validación personalizada de fecha
    document.getElementById("actividadForm").addEventListener("submit", function (event) {
        var fechaDevolucion = document.getElementById("fechaDevolucion").value;
        var fechaActual = new Date(); // Obtiene la fecha actual

        // Formatea la fecha actual en el mismo formato que fechaDevolucion
        var fechaActualFormateada = fechaActual.toISOString().slice(0, 10);

        if (fechaDevolucion === "") {
            alert("Debes seleccionar una fecha de devolución.");
            event.preventDefault(); // Evita que se envíe el formulario
        } else if (fechaDevolucion < fechaActualFormateada) {
            alert("La fecha de devolución debe ser mayor o igual a la fecha actual.");
            event.preventDefault(); // Evita que se envíe el formulario
        }
    });
});


</script>

</body>
</html>

