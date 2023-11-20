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

  <nav class="flex items-center justify-between bg-blue-500 p-6">
    <!-- Agrega la imagen al inicio del menú -->
    <!--<img src="imagenes/los_alamos_imagen.jpeg" alt="Logo" class="h-10 w-10 mr-2">-->
    
    <div class="block lg:hidden">
      <button class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
      </button>
    </div>
    <div class="w-full flex items-center justify-center lg:justify-start">
    <div class="text-lg lg:flex-grow">
        <a href="{{ route('homeAdminGuardavalores') }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
            Inicio
        </a>
        <a href="{{ route('homeClientesGV') }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
            Clientes
        </a>
        <a href="{{ route('homeGV') }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
            Guardavalores
        </a>

        @if($idRol>1)
        <a href="{{ route('homeUsuarios') }}" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
            Usuarios
        </a>
        @endif

        @if(collect($listadoPermisos)->where('indice', 'reportesGuardavalores')->first()['valor'] == 1)
        <a class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-blue-200 mr-4">
        <div class="text-lg lg:flex-grow">
        <div class="relative inline-block text-white">
            <select id="reportSelect" class="block mt-4 lg:inline-block lg:mt-0 bg-blue-500 text-white border border-white">
                <option selected>Reportes</option>
                <!--<option value="{{ route('homeReportesUnoGV') }}">Reporte de Movimientos</option>-->
                <option value="{{ route('ReporteMovGV') }}">Reporte de Movimientos en Guardavalores</option>
                <option value="{{ route('ReporteDocumentoGV') }}">Reporte por Documento</option>
                <option value="{{ route('ReporteUsuarioGV') }}">Reporte por Usuario</option>
            </select>
        </div>
        </div>
        </a>
        @endif
    
      </div>
      </div>

      <a href="{{ route('logout') }}"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
   class="text-lg px-6 py-3 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white mt-4 lg:mt-0">
   Salir
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

  </nav>
  <br>

  <h1 class="font-bold text-center">
    @if (isset($nombre))
        {{ $nombre }}
    @endif
</h1>

  <h1 class="text-2xl font-bold mb-4 text-center">ACTIVIDAD DE GUARDAVALORES</h1>

        
  <div class="w-full flex justify-center">
  <div class="w-full sm:w-4/5 md:w-4/5 lg:w-7/8 p-4 mb-1">

    <div class="table-container">

    @if(count($elementos) > 0)

      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
        <th scope="col" class="px-6 py-3" style="width: 30%;">Nombre de Documento</th>
        <th scope="col" class="px-6 py-3" style="width: 10%;">Movimiento</th>
        <th scope="col" class="px-6 py-3" style="width: 15%;">Motivo</th>
        <th scope="col" class="px-6 py-3" style="width: 15%;">Fecha</th>
        <th scope="col" class="px-6 py-3" style="width: 20%;">Nombre de usuario</th>

        </tr>
      </thead>
    </table>

    <div class="custom-scroll">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <tbody>

            @foreach($elementos as $elemento)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
              <!--<td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->tipo_gv}}
              </td>-->
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
              @if(!empty($elemento->id_documento))
                            {{ $elemento->id_documento}}
                        @else
                            {{ $elemento->tipo_gv}}
                        @endif  
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->movimiento}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->motivo}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->fecha_actividad}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                {{$elemento->id_usuario}}
              </td>
              <!--<td class="px-6 py-4 text-right">
                <a href="{{route('homeAdminExpedientes')}}">Ver</a>
              </td>-->
            </tr>
            @endforeach
          </tbody>
        </table>
      @else
        
          <img src="imagenes/los_alamos_sinfondo.png"  alt="Sin registros" class="mx-auto mt-8">
        
      @endif
    </div>
  </div>
  </div>
  </div>

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

