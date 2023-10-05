<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">

</head>
<body>


<div class="bg-white-100 h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Iniciar Sesión</h2>
        <form method="POST" action="{{ route('autenticar') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Nùmero de Usuario</label>
                <input
                    id="email"
                    type="text"
                    class="shadow appearance-none border rounded form-input w-full @error('email') border-red-500 @enderror"
                    name="email"
                    required
                    autofocus
                />
                @error('email')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
                <input
                    id="password"
                    type="password"
                    class="shadow appearance-none border rounded form-input w-full @error('registroHuellaDigital') border-red-500 @enderror"
                    name="password"
                    required
                />
                @error('registroHuellaDigital')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 text-center">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ingresar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>