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
  
    <h1 class="text-2xl font-bold mb-4 text-center md:text-left">Retirando {{$expediente->nombre}}</h1>

        <form id="actividadForm" class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-screen-md px-8 pt-8 pb-8 mb-8" action="{{route('almacenarActividadGV')}}" method="POST">
          @csrf <!-- Agrega el token CSRF para proteger el formulario -->
          
          <input type="hidden" name="tipo_gv" value="{{ $expediente->tipo_gv  }}">
          <input type="hidden" name="id_documento" value="{{ $expediente->id_documento}}">
          
          <!--<div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
              Tipo de documento: {{$expediente->tipo_gv}}
            </label>
          </div>-->

          @if(!empty($expediente->id_cliente))

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
              Acreditado: {{$expediente->id_cliente}}
            </label>
          </div>
          @endif

          
@if(!empty($expediente->numeroCertificado))
<div class="mb-4 flex">
    <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
        Número de Certificado:
    </label>
    <span class="text-gray-700 text-sm">
        {{$expediente->numeroCertificado}}
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

@if(!empty($expediente->fecha_terminacion))
<div class="mb-4 flex">
    <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
        Fecha Terminación:
    </label>
    <span class="text-gray-700 text-sm">
        {{$expediente->fecha_terminacion}}
    </span>
</div>
@endif

    
        <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="motivo">
        Motivo:
    </label>
    <textarea id="motivo" name="motivo" placeholder="Ingresa el motivo de tu solicitud" class="w-96 p-2 border border-gray-300 rounded" required maxlength="250"></textarea>
    </div>

        <div class="flex justify-center items-center space-x-4 mt-6">
               <a href="{{ route('homeGV') }}" class="bg-blue-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver</a>

            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Confirmar con huella</button>
          </div>
        </form>
    </div>
</div>


</body>
</html>

