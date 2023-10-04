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
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">

        <!-- Primer elemento card -->
        <div class="max-w-sm p-6 bg-white border border-black rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <a href="{{ route('crearContrato',$cliente->id_cliente) }}">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Contrato</h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Asignar un contrato al cliente.</p>
            <a href="{{ route('crearContrato',$cliente->id_cliente) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Seleccionar
                <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        </div>

        <!-- Segundo elemento card (copiar y pegar el código anterior para los otros dos elementos) -->
        <div class="max-w-sm p-6 bg-white border border-black rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <a href="{{ route('crearPagare',$cliente->id_cliente) }}">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Pagare</h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Asignar un nuevo pagare al cliente.</p>
            <a href="{{ route('crearPagare',$cliente->id_cliente) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Seleccionar
                <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        </div>

        <!-- Tercer elemento card -->
        <div class="max-w-sm p-6 bg-white border border-black rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <a href="{{ route('crearDocumentoValor',$cliente->id_cliente) }}">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Documento de valor</h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Asignar una nueva carta poder, escrituras u otro al cliente.</p>
            <a href="{{ route('crearDocumentoValor',$cliente->id_cliente) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Seleccionar
                <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        </div>

    </div>
</div>
</body>
</html>
