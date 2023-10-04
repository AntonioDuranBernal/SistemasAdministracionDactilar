<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">{{$expediente->nombre}}</h1>

    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-2xl w-full px-8 pt-6 pb-8 mb-4" action="{{route('retirarGV',$expediente->id_documento) }}" method="POST">
      @csrf <!-- Agrega el token CSRF para proteger el formulario -->
      
      <!-- Campo oculto para enviar el ID del cliente -->
      <input type="hidden" name="id_documento" value="{{ $expediente->id_documento}}">

      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Cliente al que pertenece:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->id_cliente}}
        </span>
      </div>

      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Número de Contrato:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->numero_contrato}}
        </span>
      </div>
      
      @if(!empty($expediente->numero_pagare))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Número de Pagare:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->numero_pagare}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->tipo_credito))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Tipo Crèdito:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->tipo_credito}}
        </span>
      </div>
      @endif
          
      @if(!empty($expediente->folio_real))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Folio Real:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->folio_real}}
        </span>
      </div>
      @endif
      
      @if(!empty($expediente->descripcion))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Observaciones:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->descripcion}}
        </span>
      </div>
      @endif
      
      @if(!empty($expediente->fecha_creacion))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Fecha Creaciòn:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->fecha_creacion}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->fecha_terminacion))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Fecha Terminaciòn:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->fecha_terminacion}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->fecha_entrega))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Fecha Entrega:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->fecha_entrega}}
        </span>
      </div>
      @endif
      
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Usuario que registró expediente:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->usuario_creador}}
        </span>
      </div>
      
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Disponibilidad:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->estado}}
        </span>
      </div>
      
      <div class="flex justify-center mt-4">

      @if($expediente->estado === 'Disponible')
      @if(collect($permisosUsuario)->where('indice', 'retirarGuardavalores')->first()['valor'] == 1)
      <div class="flex justify-center mt-4">
      <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" type="submit">
      Retirar
      </button>
      @endif
      <a href="{{ route('homeGV') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-left: 1rem;">Volver</a>
      </div>
      @else
      <div class="flex justify-center mt-4">
          <a href="{{route('homeGV')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver</a>
        </div>
      @endif

      </div>

    </form>
  </div>

</body>
</html>
