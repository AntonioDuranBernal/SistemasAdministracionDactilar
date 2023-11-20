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
    <h1 class="text-2xl font-bold mb-4 text-center">Asignar {{$nombreTipo}}</h1>
    
    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-3xl px-8 pt-6 pb-8 mb-4" action="{{ route('DVGuardar') }}" method="POST">
    @csrf <!-- Agrega el token CSRF para proteger el formulario -->

    <!-- Campo oculto para enviar el ID del cliente -->
    <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">
    <input type="hidden" name="nombreTipo" value="{{$nombreTipo}}">

    @if($nombreTipo === 'Dolares' | $nombreTipo === 'Efectivo')
    <!-- Mostrar campos específicos para 'Dólares' y 'Efectivo'
    
    <div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreDocumento">
            Nombre de Referencia
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombreDocumento" type="text" name="nombreDocumento" placeholder="Nombre de Expediente" required>
    </div> -->

    <div class="col-span-1 mt-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="cantidad">
            Cantidad
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cantidad" type="text" name="cantidad" placeholder="Cantidad" required>
    </div>

    <div class="col-span-1 mt-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaEntrega">
                Fecha de Entrega:
            </label>
            <input type="date" id="fechaEntrega" name="fechaEntrega" class="w-full p-2 border border-gray-300 rounded" required>
    </div>

    <div class="col-span-1 mt-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="funcionario">
            Funcionario
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="funcionario" type="text" name="funcionario" placeholder="Funcionario" required>
    </div>

    @elseif($nombreTipo === 'Certificado de Existencia de Gravamen' || $nombreTipo === 'Certificado' || $nombreTipo ===  'Certificado de Liberación de Gravamen')

      <input type="hidden" name="nombreDocumento" value="{{$nombreTipo}}">

      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
          ACREDITADO: {{$cliente->nombre}} <br>
          <!--Número de cliente: {{$cliente->id_cliente}}-->
        </label>
      </div>

      <!--<div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="aFavor">
                A favor de
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="aFavor" type="text" name="aFavor" placeholder="¿A favor de quien se expide?" required>
      </div>-->

      <div class="grid grid-cols-2 gap-4 mb-4">

      <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="numeroCertificado">
                Número de Certificado
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numeroCertificado" type="text" name="numeroCertificado" placeholder="Número de Certificado" required>
      </div>

      <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="monto">
                Valor
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="monto" type="text" name="monto" placeholder="Monto de la factura" required>
        </div>
    
      </div>

      <div class="grid grid-cols-2 gap-4 mb-4">
      <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="kilos">
                Kilos
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kilos" type="text" name="kilos" placeholder="Kilos" required>
        </div>

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="funcionario">
                Funcionario
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="funcionario" type="text" name="funcionario" placeholder="Funcionario" required>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-4 mb-4">

<div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaEntrega">
            Fecha de Entrega:
        </label>
        <input type="date" id="fechaEntrega" name="fechaEntrega" class="w-full p-2 border border-gray-300 rounded" required>
      </div>

      <div class="col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="observaciones">
                Observaciones
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="observaciones" type="text" name="observaciones" placeholder="Observaciones">
      </div>
      </div>

    @elseif($nombreTipo === 'Bono de Prenda')

    <input type="hidden" name="nombreDocumento" value="{{$nombreTipo}}">

<div class="mb-4">
  <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
  ACREDITADO: {{$cliente->nombre}} <br>
    <!--Número de cliente: {{$cliente->id_cliente}}-->
  </label>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">

      <!--<div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="aFavor">
                A favor de
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="aFavor" type="text" name="aFavor" placeholder="¿A favor de quien se expide?" required>
      </div>-->

<div class="col-span-1">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="numeroCertificado">
          Número de Bono de Prenda
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numeroCertificado" type="text" name="numeroCertificado" placeholder="Número de Certificado" required>
</div>

<div class="col-span-1">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="monto">
          Valor
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="monto" type="text" name="monto" placeholder="Monto de la factura" required>
  </div>

</div>

<div class="grid grid-cols-2 gap-4 mb-4">
<div class="col-span-1">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="kilos">
          Kilos
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kilos" type="text" name="kilos" placeholder="Kilos" required>
  </div>

  <div class="col-span-1">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="funcionario">
          Funcionario
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="funcionario" type="text" name="funcionario" placeholder="Funcionario" required>
  </div>
</div>
<div class="grid grid-cols-3 gap-4 mb-4">

<div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaEntrega">
            Fecha de Entrega:
        </label>
        <input type="date" id="fechaEntrega" name="fechaEntrega" class="w-full p-2 border border-gray-300 rounded" required>
      </div>
      
<div class="col-span-2">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
          Descripción
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="descripcion" type="text" name="descripcion" placeholder="Descripción">
</div>
</div>


    @elseif($nombreTipo === 'Factura')

    <input type="hidden" name="nombreDocumento" value="Factura">

      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
        ACREDITADO: {{$cliente->nombre}} <br>
          <!--Número de cliente: {{$cliente->id_cliente}}-->
        </label>
      </div>

      <div class="grid grid-cols-3 gap-4 mb-4">
      
      <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="folioReal">
                Folio
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="folioReal" type="text" name="folioReal" placeholder="Folio de factura" required>
      </div>

      <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="rfc">
                RFC
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rfc" type="text" name="rfc" placeholder="RFC" required>
        </div>

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="monto">
                Monto
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="monto" type="text" name="monto" placeholder="Monto de la factura" required>
        </div>
    
      </div>

      <div class="grid grid-cols-3 gap-4 mb-4">

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="concepto">
                Concepto
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="concepto" type="text" name="concepto" placeholder="Concepto" required>
        </div>

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="idCredito">
                ID Crédito
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="idCredito" type="text" name="idCredito" placeholder="ID Crédito">
        </div>

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="folioFiscal">
                Folio Fiscal (UUID)
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="folioFiscal" type="text" name="folioFiscal" placeholder="Folio Fiscal" required>
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
            <label class="block text-gray-700 text-sm font-bold mb-2" for="funcionario">
                Funcionario
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="funcionario" type="text" name="funcionario" placeholder="Funcionario" required>
        </div>

      <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="observaciones">
                Observaciones
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="observaciones" type="text" name="observaciones" placeholder="Observaciones">
      </div>

     </div>

    
    

    @elseif($nombreTipo === 'Fondeador')

    
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
        ACREDITADO: {{$cliente->nombre}} <br>
          <!--Número de cliente: {{$cliente->id_cliente}}-->
        </label>
      </div>
    
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreDocumento">
                Nombre
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombreDocumento" type="text" name="nombreDocumento" placeholder="Nombre de Documento" required>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="numeroContrato">
                Número de Contrato
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numeroContrato" type="text" name="numeroContrato" placeholder="Número de contrato" required>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaEntrega">
                Fecha de Entrega
            </label>
            <input type="date" id="fechaEntrega" name="fechaEntrega" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-4">
   
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="vigencia">
                Vigencia
            </label>
            <input type="date" id="vigencia" name="vigencia" class="w-full p-2 border border-gray-300 rounded" required>
        </div>

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="funcionario">
                Funcionario
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="funcionario" type="text" name="funcionario" placeholder="Funcionario" required>
        </div>
        
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="observaciones">
                Observaciones
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="observaciones" type="text" name="observaciones" placeholder="Observaciones">
        </div>
    </div>

    @elseif($nombreTipo === 'Cheque')

    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
        ACREDITADO: {{$cliente->nombre}} <br>
          <!--Número de cliente: {{$cliente->id_cliente}}-->
        </label>
      </div>

    <div class="grid grid-cols-2 gap-4">
    <input type="hidden" name="nombreDocumento" value="Cheque">

    <div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="numeroCheque">
            Número de cheque
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numeroCheque" type="text" name="numeroCheque" placeholder="Número de cheque" required>
    </div>

    <div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="cantidad">
            Cantidad
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cantidad" type="text" name="cantidad" placeholder="Cantidad" required>
    </div>

    <div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="personaExpide">
            Persona que expide
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="personaExpide" type="text" name="personaExpide" placeholder="Persona que expide" required>
    </div>

    <div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="funcionario">
            Funcionario
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="funcionario" type="text" name="funcionario" placeholder="Funcionario" required>
    </div>

    <div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaCheque">
            Fecha del Cheque:
        </label>
        <input type="date" id="fechaCheque" name="fechaCheque" class="w-full p-2 border border-gray-300 rounded" required>
    </div>

    <div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaEntrega">
            Fecha de Entrega:
        </label>
        <input type="date" id="fechaEntrega" name="fechaEntrega" class="w-full p-2 border border-gray-300 rounded" required>
    </div>


</div>


    @else

    @if($nombreTipo != 'Acta de Comite de Crédito' && $nombreTipo != 'Acta de Consejo de Administración' && $nombreTipo != 'Acta de Comite de Riesgos' )
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreCliente">
        ACREDITADO: {{$cliente->nombre}} <br>
          <!--Número de cliente: {{$cliente->id_cliente}}-->
        </label>
      </div>
      @endif

    <div class="grid grid-cols-2 gap-4 mb-4">

        <!--<div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombreDocumento">
                Nombre de Documento
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombreDocumento" type="text" name="nombreDocumento" placeholder="Nombre de Expediente" required>
        </div>-->

        @if($nombreTipo != 'Acta de Comite de Crédito' && $nombreTipo != 'Acta de Consejo de Administración' && $nombreTipo != 'Acta de Comite de Riesgos' )

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
                Número de contrato
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numero_contrato" type="text" name="descripcion" placeholder="Descripción" required>
        </div>
        @endif

    @if($nombreTipo === 'Acta de Comite de Crédito' | $nombreTipo === 'Acta de Consejo de Administración' | $nombreTipo === 'Acta de Comite de Riesgos' )
    <div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaActa">
            Fecha de Acta
        </label>
        <input type="date" id="fechaActa" name="fechaActa" class="w-full p-2 border border-gray-300 rounded" required>
    </div>

    @if($nombreTipo === 'Acta de Consejo de Administración')
    <div class="col-span-1">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="numeroEscritura">
            Número de escritura
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numeroEscritura" type="text" name="numeroEscritura" placeholder="Número de Escritura para Acta Protocolizada">
     </div>
    @endif
    
    @else

    <div class="col-span-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="folioReal">
            Folio
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="folioReal" type="text" name="folioReal" placeholder="Folio Real">
    </div>
    @endif


    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">

    @if($nombreTipo != 'Acta de Comite de Crédito' && $nombreTipo != 'Acta de Consejo de Administración' && $nombreTipo != 'Acta de Comite de Riesgos' )
    <div class="col-span-1">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="tipoCredito">
        Tipo de Crédito
    </label>
    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tipoCredito" name="tipoCredito">
        <option value="Credito">Crédito</option>
        <option value="Microcredito">Microcrédito</option>
    </select>
    </div>
    @endif

        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="funcionario">
                Funcionario
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="funcionario" type="text" name="funcionario" placeholder="Funcionario" required>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fechaEntrega">
                Fecha de Entrega:
            </label>
            <input type="date" id="fechaEntrega" name="fechaEntrega" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
    </div>


    @endif

    <div class="flex items-center justify-end mt-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" type="submit">
            Guardar
        </button>
        <a href="{{ route('homeClientesGV') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
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
