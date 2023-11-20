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
<div class="container mx-auto p-4">
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

    <!--<button id="fingerPrintButton">Capturar Huella Digital</button>-->

    <!--<div id="mensajeHuella" class="text-gray-700 text-sm font-bold mb-2"></div>-->

    <!-- Sexta fila - Botones -->
    <div class="flex items-center justify-center mt-4">
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-4" type="submit">
            Guardar
        </button>
        <a href="{{ route('homeUsuarios') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
    </div>

</form>

</div>

<!-- Estilo personalizado para los checkbox -->
<style>
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

    .checkbox-custom:checked {
        background-color: #0055a4; /* Cambia el color de fondo al seleccionar */
    }

    .checkbox-custom:checked::before {
        content: '\2713'; /* Símbolo de marca de verificación Unicode */
        color: #fff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

</body>

<script>
    // Esta función se ejecutará cuando se haga clic en el botón "Capturar Huella"
    function capturarHuella() {
        // Muestra un mensaje para indicar al usuario que coloque su dedo en el lector
        document.getElementById('mensajeHuella').textContent = 'Coloque su dedo en el lector...';

        // Aquí debes llamar a la librería @digitalpersona/devices para iniciar la lectura de la huella
        // Reemplaza esta línea con tu código real para iniciar la lectura de huella
        iniciarLecturaHuella();

        // Después de que la lectura de la huella sea exitosa, puedes mostrar un mensaje de éxito
        // Por ejemplo, si obtienes la huella con éxito desde tu lector
        setTimeout(function() {
            // Simulación de una lectura exitosa (reemplaza con tu lógica real)
            var huellaCapturada = obtenerHuellaConExito(); // Reemplaza con tu código real

            // Asigna la huella capturada al campo oculto
            document.getElementById('huella').value = huellaCapturada;

            // Muestra un mensaje de éxito
            document.getElementById('mensajeHuella').textContent = 'Lectura de huella exitosa!';
        }, 3000); // Simula una lectura exitosa después de 3 segundos (reemplaza con tu lógica real)
    }

    // Esta función simula iniciar la lectura de huella (debe reemplazarse con la lógica real)
    function iniciarLecturaHuella() {
        // Aquí debes llamar a la librería @digitalpersona/devices para iniciar la lectura de huella
        // Esto debe iniciar el proceso de lectura en tu lector UareU 2000B Reader
        // Reemplaza esta función con tu código real
    }

    // Esta función simula obtener la huella con éxito (debe reemplazarse con la lógica real)
    function obtenerHuellaConExito() {
        // Simulación de una huella capturada con éxito (reemplaza con tu código real)
        return 'HuellaCapturadaConExito'; // Reemplaza con la huella real
    }
</script>


</html>
