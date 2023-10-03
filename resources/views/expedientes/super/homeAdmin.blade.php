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
  
    @if($idRol == 3 || $idRol == 2)

    <nav class="flex items-center justify-between bg-blue-500 p-6">
    
    <div class="block lg:hidden">
      <button class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
      </button>
    </div>
    <div class="w-full flex items-center justify-center lg:justify-start">
    <div class="text-lg lg:flex-grow">
        <a href="{{ route('homeAdminExpedientes') }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
            Inicio
        </a>
        <a href="{{ route('homeClientesSuper') }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
            Clientes
        </a>
        <a href="{{ route('homeExpedientes') }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
            Expedientes
        </a>
        <a href="{{ route('homeUsuarios') }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
            Usuarios
        </a>

        <a class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
        <div class="text-lg lg:flex-grow">
        <div class="relative inline-block text-white">
            <select id="reportSelect" class="block mt-4 lg:inline-block lg:mt-0 bg-blue-500 text-white border border-white">
                <option selected>Reportes</option>
                <option value="{{ route('homeReportesUno') }}">Reporte General</option>
                <option value="{{ route('homeReportesDos') }}">Por Documento</option>
                <option value="{{ route('homeReportesTres') }}">Por Usuarios</option>
                <option value="{{ route('homeReportesCuatro') }}">Devoluciones</option>
            </select>
        </div>
        </div>
        </a>
    
      </div>
      </div>


    <a href="{{route('home')}}" class="text-lg px-6 py-3 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white mt-4 lg:mt-0">Configuración</a> <!-- Cambio de color de texto a text-white y hover:text-blue-500 -->
  </nav>
  <br>
  <h1 class="text-2xl font-bold mb-4 text-center">ACTIVIDAD DE EXPEDIENTES</h1>

  <div class="w-full flex justify-center">
    <div class="w-full sm:w-4/5 md:w-4/5 lg:w-7/8 p-4 mb-1 custom-scroll">
      @if(count($elementos) > 0)
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">
                Nombre <br> de usuario
              </th>
              <th scope="col" class="px-6 py-3">
                Nombre de expediente
              </th>
              <th scope="col" class="px-6 py-3">
                Fecha solicitud
              </th>
              <th scope="col" class="px-6 py-3">
                Fecha devolución
              </th>
              <th scope="col" class="px-6 py-3">
                Estado
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
                {{$elemento->id_usuario_solicita}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->id_expediente}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->fecha_solicitud}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->fecha_devolucion}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->estado}}
              </td>
              <!--<td class="px-6 py-4 text-right">
                <a href="{{route('homeAdminExpedientes')}}">Ver</a>
              </td>-->
            </tr>
            @endforeach
          </tbody>
        </table>
      @else
          <img src="imagenes/los_alamos_sinfondo.png" alt="Sin registros" class="mx-auto mt-8">
      @endif
    </div>
  </div>
    
    @else
    <nav class="flex items-center justify-between bg-blue-500 p-6">
    
    <div class="block lg:hidden">
      <button class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Inicio</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
      </button>
    </div>
    <div class="w-full flex items-center justify-center lg:justify-start">
      <div class="text-lg lg:flex-grow">
        <a href="{{ route('expedientesBasico',$usuario) }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
          Inicio
        </a>
        <a href="{{route('homeClientesUsuario',$usuario)}}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
          Clientes
        </a>
        <a href="{{route('homeExpedientesUB',$usuario)}}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
          Expedientes
        </a>

        @if(collect($permisosUsuario)->where('indice', 'reportesExpediente')->first()['valor'] == 1)
        
        <a class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
        <div class="text-lg lg:flex-grow">
        <div class="relative inline-block text-white">
            <select id="reportSelect" class="block mt-4 lg:inline-block lg:mt-0 bg-blue-500 text-white border border-white">
                <option selected>Reportes</option>
                <option value="{{ route('homeReportesUno') }}">Reporte General</option>
                <option value="{{ route('homeReportesDos') }}">Por Documento</option>
                <option value="{{ route('homeReportesTres') }}">Por Usuarios</option>
                <option value="{{ route('homeReportesCuatro') }}">Devoluciones</option>
            </select>
        </div>
        </div>
        </a>

         @endif

      </div>
    </div>
    <a href="{{route('home')}}" class="text-lg px-6 py-3 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white mt-4 lg:mt-0">Configuración</a> <!-- Cambio de color de texto a text-white y hover:text-blue-500 -->
  </nav>
  <br>
  <h1 class="text-2xl font-bold mb-4 text-center">ACTIVIDAD DE EXPEDIENTES</h1>

  <div class="w-full flex justify-center">
    <div class="w-full sm:w-4/5 md:w-4/5 lg:w-7/8 p-4 mb-1 custom-scroll">
      @if(count($elementos) > 0)
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">
                Nombre <br> de usuario
              </th>
              <th scope="col" class="px-6 py-3">
                Nombre de expediente
              </th>
              <th scope="col" class="px-6 py-3">
                Fecha solicitud
              </th>
              <th scope="col" class="px-6 py-3">
                Fecha devolución
              </th>
              <th scope="col" class="px-6 py-3">
                Estado
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
                {{$elemento->id_usuario_solicita}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->id_expediente}}
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
              @if($elemento->estado == 'En uso' || $elemento->estado == 'Devolución atrasada')
              <a href="{{route('devolverExpediente',['id_e' => $elemento->id_expediente, 'id_u' => $usuario, 'id_a' => $elemento->id_actividad] )}}">Devolver</a>
              @endif
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

    @endif

</body>
</html>


<script>
    const select = document.getElementById('reportSelect');
    const optionBackground = document.getElementById('optionBackground');

    select.addEventListener('change', function () {
        const selectedValue = select.value;
        window.location.href = selectedValue;
    });

    select.addEventListener('focus', function () {
        optionBackground.style.opacity = '1';
    });

    select.addEventListener('blur', function () {
        optionBackground.style.opacity = '0';
    });
</script>


