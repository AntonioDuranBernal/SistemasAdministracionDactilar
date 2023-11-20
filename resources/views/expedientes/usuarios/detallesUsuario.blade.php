<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>
<body>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">{{$usuario->nombre}} {{$usuario->apellidos}}</h1>
    
    <div class="bg-white border border-gray-300 shadow-lg rounded-md mx-auto max-w-3xl w-3/4 px-8 pt-6 pb-8 mb-4">
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          ID Usuario:
        </label>
        <span class="text-gray-700 text-sm">
          {{$usuario->idUsuarioSistema}}
        </span>
      </div>
            
      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Otros Datos:
        </label>
        <span class="text-gray-700 text-sm">
          {{$usuario->otros_datos}}
        </span>
      </div>

      <div class="mb-4 flex">
        <label class="block text-gray-700 text-sm font-bold mb-2 w-1/3">
          Rol:
        </label>
        <span class="text-gray-700 text-sm">
          @if ($usuario->rol == 1)
            Usuario Básico
          @elseif ($usuario->rol == 2)
            Administrador
          @elseif ($usuario->rol == 3)
            Super Usuario
          @else
            Desconocido
          @endif
        </span>
      </div>
      
<!-- Datos de Expedientes -->
<div class="mb-4 flex">
  <label class="block text-gray-700 font-bold  text-sm mb-2 w-1/3">
    Permisos en Expedientes:
  </label>
</div>

<div class="mb-4 flex ">
  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Registrar:           @if ($usuario->registrarExpediente)
      Sí
    @else
      No
    @endif
  </label>

  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Consultar:           @if ($usuario->consultarExpediente)
      Sí
    @else
      No
    @endif
  </label>
  <span class="text-gray-700 text-sm">

  </span>

  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Editar:           @if ($usuario->editarExpediente)
      Sí
    @else
      No
    @endif
  </label>

  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Eliminar:           @if ($usuario->eliminarExpediente)
      Sí
    @else
      No
    @endif
  </label>

  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Reportes:           @if ($usuario->reportesExpediente)
      Sí
    @else
      No
    @endif
  </label>
</div>

<!-- Datos de Guardavalores -->
<div class="mb-4 flex">
  <label class="block text-gray-700 font-bold  text-sm mb-2 w-1/3">
    Permisos en Guardavalores:
  </label>
</div>

<div class="mb-4 flex">
  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Registrar:           @if ($usuario->registrarGuardavalores)
      Sí
    @else
      No
    @endif
  </label>

  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Retirar:           @if ($usuario->retirarGuardavalores)
      Sí
    @else
      No
    @endif
  </label>

  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Editar:           @if ($usuario->editarGuardavalores)
      Sí
    @else
      No
    @endif
  </label>

  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Consultar:           @if ($usuario->consultarGuardavalores)
      Sí
    @else
      No
    @endif
  </label>

  <label class="block text-gray-700 text-sm mb-2 w-1/3">
    Reportes:           @if ($usuario->reportesGuardavalores)
      Sí
    @else
      No
    @endif
  </label>
</div>

      <div class="flex justify-center mt-4">
        <a href="{{route('homeUsuarios')}}"  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver</a>
      
        <a href="{{ route('usuario.edit', $usuario->idUsuarioSistema) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-left: 1rem;">Editar</a>
      
        <a href="{{ route('borrarUsuario', $usuario->idUsuarioSistema) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-left: 1rem;">Eliminar</a>
      </div>

    </div>
  </div>
</body>
</html>
