<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Detalles de Tomo {{$expediente->nombre}}</h1>
    
    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-2xl w-full px-8 pt-6 pb-8 mb-4" action="{{route('solicitarExpediente', $expediente->id_expediente )}}" method="POST">
      @csrf <!-- Agrega el token CSRF para proteger el formulario -->
      
      <!-- Campo oculto para enviar el ID del cliente -->
      <input type="hidden" name="id_expediente" value="{{ $expediente->id_expediente}}">
      
      <!--<div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Número de Expediente:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->id_expediente}}
        </span>
      </div>-->
      
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Expediente:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->id_cliente}}
        </span>
      </div>
      
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Descripción:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->descripcion}}
        </span>
      </div>
      
      @if(!empty($expediente->folio_real))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Tomo:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->folio_real}}
        </span>
      </div>
      @endif
      
      @if(!empty($expediente->otros_datos))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Otros Datos:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->otros_datos}}
        </span>
      </div>
      @endif
      
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Fecha de creación:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->fecha_creacion}}
        </span>
      </div>

      @if (!empty($expediente->usuario_creador))
    <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
            Usuario que registró:
        </label>
        <span class="text-gray-700 text-sm">
            {{$expediente->usuario_creador}}
        </span>
    </div>
@endif
      <div class="mb-4 flex">
    <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
        Disponibilidad:
    </label>
    <span class="text-gray-700 text-sm">
        {{$expediente->estado}}
    </span>
</div>

@if ($expediente->estado != 'Disponible' && !empty($expediente->usuario_posee))
<div class="mb-4 flex">
    <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
        Usuario que retiró:
    </label>
    <span class="text-gray-700 text-sm">
        {{$expediente->usuario_posee}}
    </span>
</div>
@endif

      
      @if($expediente->estado == 'Disponible')
      <div class="flex justify-center mt-4">
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" type="submit">
         Solicitar
        </button>

        @if(collect($permisosUsuario)->where('indice', 'editarExpediente')->first()['valor'] == 1)
        <a href="{{ route('editarExp',$expediente->id_expediente) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-left: 1rem;">Editar</a>
        @endif

        @if(collect($permisosUsuario)->where('indice', 'eliminarExpediente')->first()['valor'] == 1)
      <a href="{{ route('borrarExpediente', $expediente->id_expediente) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-left: 1rem;">Eliminar</a>
      @endif

      
      <a href="{{ route('homeExpedientes') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-left: 1rem;">Volver</a>
      
      </div>

      <!--
        <a href="{{ route('borrarExpediente', $expediente->id_expediente) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-left: 1rem;">Eliminar</a>
      -->

      @else
        <div class="flex justify-center mt-4">
          <a href="{{route('homeExpedientes')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver</a>
        </div>
      @endif
      
    </form>
  </div>
</body>
</html>
