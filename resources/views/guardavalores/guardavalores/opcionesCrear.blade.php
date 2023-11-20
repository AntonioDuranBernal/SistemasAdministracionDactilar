<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Tipo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>

<div class="grid grid-cols-12 gap-4">
    <div class="col-span-2 p-6">
        <a href="{{ route('homeClientesGV') }}">
            <button class="w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 text-white font-medium rounded-lg text-sm py-4">Volver</button>
        </a>
    </div>
</div>

<input type="hidden" name="cliente_id" value="{{$cliente->id_cliente}}">

<div class="flex justify-center mt-8">
    <div class="grid grid-cols-1 gap-4 sm:gap-8 md:grid-cols-3 lg:grid-cols-5 lg:ml-16 lg:mr-16">

        <!-- Opción 2: Pagare -->
            <a href="{{ route('crearPagare', $cliente->id_cliente) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Pagare
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

           <!-- Opción 1: Contrato -->
           <a href="{{ route('crearContrato',$cliente->id_cliente) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            <span class="text-center">Contrato</span>
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        <!-- Opción 6: Acta de Comite de Crédito -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Acta de Comite de Crédito']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Actas de Comite de Credito
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        <!-- Opción 7: Acta de Consejo Administrativo -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Acta de Consejo de Administración']) }}"  class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Actas de Consejo de Administración
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        <!-- Opción 11: Actas de Comite de Riesgos -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Acta de Comite de Riesgos']) }}"  class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Actas de Comite de Riesgos
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        
        <!-- Opción 15: Cheques -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Cheque']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Cheques
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        <!-- Opción 16: Dolares -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Dolares']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Dólares
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        <!-- Opción 17: Efectivo -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Efectivo']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Efectivo
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        <!-- Opción 19: Fondeadores -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Fondeador']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Fondeadores
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill"none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        <!-- Opción 8: Facturas -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Factura']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Facturas
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

         <!-- Opción 13: Certificados de Existencia de Gravamen -->
         <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Certificado de Existencia de Gravamen']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Certificados de Existencia de Gravamen
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        <!-- Opción 9: Certificados -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Certificado']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Certificados
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

                  <!-- Opción 3: Certificado de Liberacion de Gravamen -->
                  <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Certificado de Liberación de Gravamen']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Certificados de Liberación de Gravamen
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

                <!-- Opción 10: Bonos de Prenda -->
                <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Bono de Prenda']) }}"  class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Bonos de Prenda
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

                <!-- Opción 18: Avaluos -->
                <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Avaluo']) }}"  class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Avaluos
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill"none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

                <!-- Opción 5: Escrituras -->
                <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Escritura']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Escrituras
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>

        <!-- Registro Público de la Propiedad -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Registro Público de la Propiedad']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
        Registro Publico de la Propiedad
        <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
        </svg>
       </a>

        <!-- Opción 12: Poderes -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Carta Poder']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Poderes
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>


        <!-- Opción 14: Pedimentos -->
        <a href="{{ route('crearDocumentoValorX', [$cliente->id_cliente, 'Pedimento']) }}" class="inline-flex items-center justify-between px-2 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
            Pedimentos
            <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0m-4 4L9 1m4 4L9 9"/>
            </svg>
        </a>



        
    </div>
</div>
</body>
</html>
