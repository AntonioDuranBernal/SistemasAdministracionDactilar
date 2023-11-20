<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Asignar Pagare</h1>
    
    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-3xl px-8 pt-6 pb-8 mb-4" action="{{ route('pagareGuardar') }}" method="POST">
    @csrf <!-- Agrega el token CSRF para proteger el formulario -->

    <!-- Campo oculto para enviar el ID del cliente -->
    <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
          ACREDITADO: {{$cliente->nombre}} <br>
          <!--Número de cliente: {{$cliente->id_cliente}}-->
        </label>
      </div>

    <div class="grid grid-cols-3 gap-4 mb-4">

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="numero_credito">
                ID de Credito
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numero_credito" type="text" name="numero_credito" placeholder="Número de credito" required>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="numero_pagare">
                Número de Pagare
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numero_pagare" type="text" name="numero_pagare" placeholder="Número de pagare" required>
        </div>

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="numero_contrato">
                Número de Contrato
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numero_contrato" type="text" name="numero_contrato" placeholder="Número de contrato" required>
        </div>

    </div>

    <div class="grid grid-cols-3 gap-4 mb-4">

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaEntrega">
                Fecha de Entrega:
            </label>
            <input type="date" id="fechaEntrega" name="fechaEntrega" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaTerminacion">
                Fecha de Terminación
            </label>
            <input type="date" id="fechaTerminacion" name="fechaTerminacion" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="monto">
                Monto
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="monto" type="text" name="monto" placeholder="Monto" required>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="funcionario">
                Funcionario
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="funcionario" type="text" name="funcionario" placeholder="Funcionario" required>
        </div>
        <div class="col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="observaciones">
                Observaciones
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="observaciones" type="text" name="observaciones" placeholder="Observaciones">
        </div>
    </div>

    <div class="flex items-center justify-end mt-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" type="submit">
            Guardar
        </button>
        <a href="{{route('homeClientesGV')}}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
    </div>
</form>

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
        var fechaMinima = "<?php echo $fecha; ?>";

        if (fechaDevolucion === "") {
            alert("Debes seleccionar una fecha de entrega.");
            event.preventDefault(); // Evita que se envíe el formulario
        } else if (fechaDevolucion < fechaMinima) {
            alert("La fecha de entrega debe ser mayor o igual a la fecha actual.");
            event.preventDefault(); // Evita que se envíe el formulario
        }
    });
});


</script>

</body>
</html>
