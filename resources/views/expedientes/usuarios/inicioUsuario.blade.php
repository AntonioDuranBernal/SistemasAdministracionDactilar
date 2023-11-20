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

<script>

        setTimeout(function () {
        var successMessage = document.querySelector('.alert-error');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000); // 10000 milisegundos = 10 segundos

    setTimeout(function () {
        var successMessage = document.querySelector('.alert-success');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000); // 10000 milisegundos = 10 segundos

    setTimeout(function () {
        var successMessage = document.querySelector('.alert-info');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000); // 10000 milisegundos = 10 segundos

</script>


</head>
<body>

  <nav class="flex items-center justify-between bg-blue-500 p-6">

  @if(session('info'))
    <div class="alert-info bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Información:</strong>
        <span class="block sm:inline">{{ session('info') }}</span>
    </div>
   @endif

    @if(session('success'))
    <div class="alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Éxito!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif
    
    <div class="block lg:hidden">
      <button class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
      </button>
    </div>
    <div class="w-full flex items-center justify-center lg:justify-start">
      <div class="text-lg lg:flex-grow">
        <a href="{{route('expedientesBasico',$usuario->idUsuarioSistema)}}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
          Inicio
        </a>
        <a href="{{route('clientesBasico,$usuario->idUsuarioSistema)}}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
          Clientes
        </a>
        <a href="{{route('home')}}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
          Expedientes
        </a>
         @if(collect($permisosUsuario)->where('indice', 'reportesExpediente')->first()['valor'] == 1)
        <a href="{{ route('home') }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
        Reportes
        </a>
         @endif
      </div>
    </div>
    <a href="{{route('home')}}" class="text-lg px-6 py-3 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white mt-4 lg:mt-0">Configuración</a> <!-- Cambio de color de texto a text-white y hover:text-blue-500 -->
  </nav>
  <br>
  <div class="w-full flex justify-center">
    <div class="w-full sm:w-4/5 md:w-4/5 lg:w-7/8 p-4 mb-1 custom-scroll">
      @if(count($elementos) > 0)
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">
                Nombre de <br>Expediente
              </th>
              <th scope="col" class="px-6 py-3">
                Motivo
              </th>
              <th scope="col" class="px-6 py-3">
                Fecha solicitud
              </th>
              <th scope="col" class="px-6 py-3">
                Fecha devolución
              </th>
              <th scope="col" class="px-6 py-3">
                Disponibilidad
              </th>
              <th scope="col" class="px-6 py-3">
                <span class="sr-only">Opción</span>
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach($elementos as $elemento)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->nombre}} ({{$elemento->id_expediente}})
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->motivo}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
              @php
                $fechaSolicitud = date('d/m/Y', strtotime($elemento->fecha_solicitud));
                @endphp
                {{$fechaSolicitud}}   
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                @php
                $fechaD = date('d/m/Y', strtotime($elemento->fecha_devolucion));
                @endphp
                {{$fechaD}} 
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->estado}}
              </td>
              <td class="px-6 py-4 text-right">
                <a href="{{route('home')}}">Entregar</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <!--<img src="imagenes/los_alamos_sinfondo.png" alt="Sin registros" class="mx-auto mt-8">-->
      @endif
    </div>
  </div>
</body>
</html>
