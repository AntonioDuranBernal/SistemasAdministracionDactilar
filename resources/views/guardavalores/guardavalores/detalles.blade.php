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

      @if(!empty($expediente->id_cliente))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Acreditado:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->id_cliente}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->numeroCertificado))
<div class="mb-4 flex">
    <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
        Número de certificado:
    </label>
    <span class="text-gray-700 text-sm">
        {{$expediente->numeroCertificado}}
    </span>
</div>
@endif


      @if(!empty($expediente->numero_contrato))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Número de Contrato:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->numero_contrato}}
        </span>
      </div>
      @endif

      
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

      @if(!empty($expediente->numeroCheque))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Número de cheque:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->numeroCheque}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->personaExpide))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Persona que expide:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->personaExpide}}
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

      @if(!empty($expediente->vigencia))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Vigencia:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->vigencia}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->folio_real))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Folio:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->folio_real}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->folioFiscal))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Folio fiscal
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->folioFiscal}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->rfc))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          RFC:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->rfc}}
        </span>
      </div>
      @endif


      @if(!empty($expediente->fecha_acta))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Fecha de Acta:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->fecha_acta}}
        </span>
      </div>
      @endif
          
      @if(!empty($expediente->concepto))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Concepto:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->concepto}}
        </span>
      </div>
      @endif


      @if(!empty($expediente->numero_cheque))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Número de cheque:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->numero_cheque}}
        </span>
      </div>
      @endif
      
      @if(!empty($expediente->cantidad))
<div class="mb-4 flex">
    <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
        Cantidad:
    </label>
    <span class="text-gray-700 text-sm">
        ${{ number_format($expediente->cantidad, 2) }}
    </span>
</div>
@endif




      @if(!empty($expediente->fechaCheque))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Fecha de cheque:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->fechaCheque}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->monto))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Monto:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->monto}}
        </span>
      </div>
      @endif

      @if(!empty($expediente->kilos))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Kilos:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->kilos}}
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

      @if(!empty($expediente->funcionario))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Funcionario:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->funcionario}}
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

      <!--
      @if(!empty($expediente->usuario_creador))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Usuario que registr:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->descripcion}}
        </span>
      </div>
      @endif-->

      <!--
      @if(!empty($expediente->fecha_creacion))
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Fecha Creaciòn:
        </label>
        <span class="text-gray-700 text-sm">
          {{$expediente->fecha_creacion}}
        </span>
      </div>
      @endif-->

    <div class="mb-4 flex">
    <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
        Estado:
    </label>
    <span class="text-gray-700 text-sm">
        {{$expediente->estado}}
    </span>
    </div>

@if ($expediente->estado == 'Retirado')
<div class="mb-4 flex">
    <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
        Usuario que retiró:
    </label>
    <span class="text-gray-700 text-sm">
        {{$expediente->usuario_posee}}
    </span>
</div>
@endif
    
      
      <div class="flex justify-center mt-4">

      @if($expediente->estado === 'Disponible')

      @if(collect($permisosUsuario)->where('indice', 'retirarGuardavalores')->first()['valor'] == 1)
      <div class="flex justify-center mt-4">
      <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" type="submit">
      Retirar
      </button>
      @endif

      @if(collect($permisosUsuario)->where('indice', 'editarGuardavalores')->first()['valor'] == 1)
      <a href="{{ route('editarGV',$expediente->id_documento) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-left: 1rem;">Editar</a>
      @endif

      <a href="{{ route('homeGV') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-left: 1rem;">Volver</a>
  
    </div>
      @else

      @if($expediente->estado === 'Retirado')

      @if(collect($permisosUsuario)->where('indice', 'retirarGuardavalores')->first()['valor'] == 1)
      
      <div class="flex justify-center mt-4">

      <a href="{{ route('reingresar', $expediente->id_documento) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-4">Reingresar</a>

      <!--@if ($expediente->tipo_gv != 'Factura' && $expediente->tipo_gv != 'Efectivo' && $expediente->tipo_gv != 'Dolares' && $expediente->tipo_gv != 'Cheque' && $expediente->tipo_gv != 'Factura')
      <a href="{{ route('reingresar', $expediente->id_documento) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-4">Reingresar</a>
      @endif-->

      
      @endif
      @endif

      <a href="{{route('homeGV')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver</a>
    </div>
        
      @endif

      </div>

    </form>
  </div>

</body>
</html>
