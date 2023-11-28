

<!-- Resto de tu contenido actual -->

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Cliente</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
  
  <!--<script src="node_modules/@digitalpersona/devices/modules/websdk/index.js"></script>
  <script src="websdk/websdk.client.ui.min.js"></script>-->

  <script src="core/modules/WebSdk/index.js"></script>
  <script type="text/javascript" src="websdk/websdk.client.ui.min.js"></script>

</head>
<body>
<div class="container mx-auto p-4 relative">


<div id="miCard" class="hidden absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <!-- Contenido del card 

    <a href="#" onclick="cerrarCard()" class="flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Confirmar
        </a>

    -->
    <a href="#" class="flex justify-center items-center">
        <img class="rounded-t-lg" src="{{ asset('imagenes/huella.png') }}" alt="" />
    </a>
    <div class="p-5">
        <a href="#" class="p-5 flex justify-center items-center">
            <h5 class="flex justify-center items-center mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Colocar dedo índice en lector.</h5>
        </a>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"></p>
        
    </div>
</div>




    <h1 class="text-2xl font-bold mb-4 text-center">Registrar Usuario</h1>
    <form class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto px-8 pt-6 pb-8 mb-4" action="{{ route('usuario.store') }}" method="POST">
    @csrf <!-- Agregar el token CSRF aquí -->
    <input type="hidden" name="huella" id="huella">

    <!-- Primera fila -->
    <div class="mb-4 grid grid-cols-2 gap-4">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                Nombre(s)
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" type="text" name="nombre" placeholder="Nombre(s)" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="apellidos">
                Apellidos
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="apellidos" type="text" name="apellidos" placeholder="Apellidos" required>
        </div>
    </div>

    <!-- Segunda fila -->
    <div class="mb-4 grid grid-cols-2 gap-4">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="otros_datos">
                Observaciones
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="otros_datos" type="text" name="otros_datos" placeholder="Observaciones" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="rol">
                Rol
            </label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rol" name="rol" required>
                <option value="1">Usuario</option>
                <option value="2">Administrador</option>
                <!--<option value="3">Super Administrador</option>-->
            </select>
        </div>
    </div>

    <!-- Tercera fila - Expedientes -->
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">
            Expedientes
        </label>
        <div class="grid grid-cols-5 gap-4">
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="expediente_registrar" id="expediente_registrar">
                <label for="expediente_registrar">Registrar Expedientes</label>
            </div>
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="expediente_consultar" id="expediente_consultar">
                <label for="expediente_consultar">Consultar Expedientes</label>
            </div>
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="expediente_editar" id="expediente_editar">
                <label for="expediente_editar">Editar Expedientes</label>
            </div>
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="expediente_eliminar" id="expediente_eliminar">
                <label for="expediente_eliminar">Eliminar Expedientes</label>
            </div>
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="expediente_reportes" id="expediente_reportes">
                <label for="expediente_reportes">Reportes Expedientes</label>
            </div>
        </div>
    </div>

    <!-- Cuarta fila - Guardavalores -->
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">
            Guardavalores
        </label>
        <div class="grid grid-cols-5 gap-4">
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="guardavalor_registrar" id="guardavalor_registrar">
                <label for="guardavalor_registrar">Registrar Guardavalores</label>
            </div>
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="guardavalor_retirar" id="guardavalor_retirar">
                <label for="guardavalor_retirar">Retirar Guardavalores</label>
            </div>
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="guardavalor_editar" id="guardavalor_editar">
                <label for="guardavalor_editar">Editar Guardavalores</label>
            </div>
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="guardavalor_consultar" id="guardavalor_consultar">
                <label for="guardavalor_consultar">Consultar Guardavalores</label>
            </div>
            <div>
                <input class="checkbox-custom" type="checkbox" name="permisos[]" value="guardavalor_reportes" id="guardavalor_reportes">
                <label for="guardavalor_reportes">Reportes Guardavalores</label>
            </div>
        </div>
    </div>



    <!--    <button onclick="mostrarAlerta()"  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Capturar Huella</button>

    -->
    <button onclick="mostrarCard()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Capturar Huella</button>



    <!-- Sexta fila - Botones -->
    <div class="flex items-center justify-center mt-4">
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-4" type="submit">
            Guardar
        </button>
        <a href="{{ route('homeUsuarios') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
    </div>

</form>

</div>

<!-- Estilo personalizado para los checkbox 

    /* Añade cualquier estilo adicional que desees */
        .checkbox-custom {
        appearance: none;
        border: 2px solid #000;
        width: 1.2rem;
        height: 1.2rem;
        display: inline-block;
        position: relative;
        vertical-align: middle;
        margin-right: 0.5rem;
    }

-->
<style>    

    .checkbox-custom:checked {
        background-color: #0055a4; /* Cambia el color de fondo al seleccionar */
    }

    .checkbox-custom:checked::before {
        content: '\2713'; /* Símbolo de marca de verificación Unicode */
        color: #fff;
        top: 50%;
        left: 50%;
        position: absolute;
        transform: translate(-50%, -50%);
    }

#miCard {
        max-width: 30rem; /* Ancho máximo del card */
        background-color: white; /* Color de fondo del card */
        opacity: 1; /* Ajusta la opacidad del card */
        visibility: visible; /* Asegura que el card sea visible por defecto */
        transition: opacity 0.3s ease; /* Agrega transición suave para la opacidad */
    }

    /* Estilo adicional para el card cuando está oculto */
    #miCard.hidden {
        opacity: 0; /* Establece la opacidad en 0 cuando está oculto */
        visibility: hidden; /* Oculta el card cuando está oculto */
    }



</style>

<!-- Agrega esta línea al final del archivo HTML, antes de </body> -->
<script src="{{ asset('js/funciones.js') }}"></script>

</body>

</html>
