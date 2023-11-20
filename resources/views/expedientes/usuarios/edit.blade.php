<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Editar Usuario</h1>
    <form action="{{ route('usuario.update', $usuario->idUsuarioSistema) }}" method="POST">
        @csrf
        @method('PUT') <!-- Método HTTP para la actualización -->

        <!-- Fila 1: Nombre y Apellidos -->
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                    Nombre(s)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" type="text" name="nombre" value="{{ $usuario->nombre }}" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="apellidos">
                    Apellidos
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="apellidos" type="text" name="apellidos" value="{{ $usuario->apellidos }}" required>
            </div>
        </div>

        <!-- Fila 2: Rol y Observaciones -->
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="rol">
                    Rol
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rol" name="rol" required>
                    <option value="1" @if($usuario->rol === '1') selected @endif>Usuario</option>
                    <option value="2" @if($usuario->rol === '2') selected @endif>Administrador</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="otros_datos">
                    Observaciones
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="otros_datos" type="text" name="otros_datos" value="{{ $usuario->otros_datos }}" required>
            </div>
        </div>

        <!-- Tercera fila - Expedientes -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Expedientes
            </label>
            <div class="grid grid-cols-5 gap-4">
                <!-- Crea checkboxes para los permisos de expedientes -->
                <div>
                    <input class="checkbox-custom" type="checkbox" name="expediente_registrar" value="1" @if($usuario->registrarExpediente == 1) checked @endif>
                    <label>Registrar Expedientes</label>
                </div>
                <div>
                    <input class="checkbox-custom" type="checkbox" name="expediente_consultar" value="1" @if($usuario->consultarExpediente == 1) checked @endif>
                    <label>Consultar Expedientes</label>
                </div>
                <div>
                    <input class="checkbox-custom" type="checkbox" name="expediente_editar" value="1" @if($usuario->editarExpediente == 1) checked @endif>
                    <label>Editar Expedientes</label>
                </div>
                <div>
                    <input class="checkbox-custom" type="checkbox" name="expediente_eliminar" value="1" @if($usuario->eliminarExpediente == 1) checked @endif>
                    <label>Eliminar Expedientes</label>
                </div>
                <div>
                    <input class="checkbox-custom" type="checkbox" name="expediente_reportes" value="1" @if($usuario->reportesExpediente == 1) checked @endif>
                    <label>Reportes Expedientes</label>
                </div>
            </div>
        </div>

        <!-- Cuarta fila - Guardavalores -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Guardavalores
            </label>
            <div class="grid grid-cols-5 gap-4">
                <!-- Crea checkboxes para los permisos de guardavalores -->
                <div>
                    <input class="checkbox-custom" type="checkbox" name="guardavalor_registrar" value="1" @if($usuario->registrarGuardavalores == 1) checked @endif>
                    <label>Registrar Guardavalores</label>
                </div>
                <div>
                    <input class="checkbox-custom" type="checkbox" name="guardavalor_retirar" value="1" @if($usuario->retirarGuardavalores == 1) checked @endif>
                    <label>Retirar Guardavalores</label>
                </div>
                <div>
                    <input class="checkbox-custom" type="checkbox" name="guardavalor_editar" value="1" @if($usuario->editarGuardavalores == 1) checked @endif>
                    <label>Editar Guardavalores</label>
                </div>
                <div>
                    <input class="checkbox-custom" type="checkbox" name="guardavalor_consultar" value="1" @if($usuario->consultarGuardavalores == 1) checked @endif>
                    <label>Consultar Guardavalores</label>
                </div>
                <div>
                    <input class="checkbox-custom" type="checkbox" name="guardavalor_reportes" value="1" @if($usuario->reportesGuardavalores == 1) checked @endif>
                    <label>Reportes Guardavalores</label>
                </div>
            </div>
        </div>

        <!-- Botón para guardar cambios -->
        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Guardar Cambios
        </button>
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
</html>
