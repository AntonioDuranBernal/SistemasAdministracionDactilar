<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
  
  <style>
    /* Estilo para el contenedor del scroll */
    .custom-scroll {
      max-height: 400px; /* Ajusta la altura máxima del scroll según tus necesidades */
      overflow-y: auto;
      border: 1px solid #e2e8f0; /* Color del borde del scroll */
      border-radius: 0.375rem; /* Borde redondeado */
      background-color: #f7fafc; /* Color de fondo del scroll */
      scrollbar-width: thin; /* Ancho del scrollbar en navegadores Firefox */
      scrollbar-color: #4299e1 #f7fafc; /* Color del scrollbar en navegadores Firefox */
    }

    /* Estilo para las barras de desplazamiento en navegadores Chrome */
    .custom-scroll::-webkit-scrollbar {
      width: 6px; /* Ancho del scrollbar en navegadores Chrome */
    }

    /* Estilo para el pulgar del scrollbar en navegadores Chrome */
    .custom-scroll::-webkit-scrollbar-thumb {
      background-color: #4299e1; /* Color del pulgar del scrollbar en navegadores Chrome */
      border-radius: 3px; /* Borde redondeado del pulgar */
    }
  </style>
</head>
<body>

<div class="w-full flex justify-center">
    <div class="w-full sm:w-4/5 md:w-4/5 lg:w-7/8 p-4 mb-1">



    {{-- Esta es la fila con 3 elementos --}}
<div class="grid grid-cols-12 gap-4">
  
<div class="col-span-2 flex-grow flex-shrink p-6">
    <a href="{{ route('homeAdminExpedientes') }}">
        <button class="w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 text-white font-medium rounded-lg text-sm py-4">Volver</button>
    </a>
</div>

<div class="col-span-10 h-[100px] p-3"> <!-- Quita la clase bg-gray-200 y aplica una altura fija -->
    <br>
    <h1 class="text-2xl font-bold mb-4 text-center">REPORTE DE MOVIMIENTOS EN EXPEDIENTES</h1>
    </div>
</div>


<form action="{{ route('ejecutarExpedienteGeneralSU') }}" method="POST" class="mb-4">
    @csrf
    <input type="hidden" name="elementos" value="{{ json_encode($elementos) }}">

        <div class="flex gap-4">
            <div class="w-1/2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_inicio">
            Fecha Inicio:
            </label>
            <input type="date" id="fecha_inicio" name="fecha_inicio"  class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="w-1/2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_fin">
        Fecha Fin:
        </label>
        <input type="date" id="fecha_fin" name="fecha_fin" class="w-full p-2 border border-gray-300 rounded">
        </div>
        </div>
        <div class="flex justify-center gap-4">
            <button type="submit" class="mt-5 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg text-sm py-2 px-4">Ejecutar</button>
            

            @if(count($elementos) > 0)
            <a href="{{ route('exportarExpedientes', ['elementos' => $elementos]) }}" class="mt-5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg text-sm py-2 px-4">Exportar</a>
    @else
    <a class="mt-5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg text-sm py-2 px-4">Exportar</a>
    @endif
        
        </div>
        <br>
                    @if(count($elementos) > 0)
            <div class="custom-scroll">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tomo
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Movimiento
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Usuario Solicitud
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fecha Solicitud
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fecha a Devolver
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fecha de Entrega
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Disponibilidad
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($elementos as $elemento)
<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
    {{$elemento->tomo}}
    </td>
    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
        {{$elemento->id_usuario_otorga}}
    </td>
    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
    {{$elemento->Movimiento}}
    </td>
    
    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
        @if ($elemento->id_usuario_solicita)
        {{$elemento->id_usuario_solicita}}
        @else
        - - -
        @endif
    </td>

    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
        @if ($elemento->fecha_solicitud)
            {{$elemento->fecha_solicitud}}
        @else
        - - -
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
        @if ($elemento->fecha_devolucion)
            {{$elemento->fecha_devolucion}}
        @else
        - - -
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
        @if ($elemento->fecha_entrega)
            {{$elemento->fecha_entrega}}
        @else
        - - -
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap dark:text-white {{ $elemento->estado === 'Devolución atrasada' ? 'text-red-500' : '' }}">
    {{ $elemento->estado }}
</td>
</tr>
@endforeach

                </tbody>
            </table>
        </div>
        @else
         <img src="imagenes/los_alamos_sinfondo.png" alt="Sin registros" class="mx-auto mt-8">
      @endif
 
      </form>

</div>
</div>

<script>
function validateDate() {
    var startDate = document.getElementById("fecha_inicio").value;
    var endDate = document.getElementById("fecha_fin").value;
    
    if (startDate > endDate) {
        alert("La fecha de fin debe ser igual o mayor que la fecha de inicio");
        document.getElementById("fecha_fin").value = startDate;
    }
}
</script>

</body>
</html>