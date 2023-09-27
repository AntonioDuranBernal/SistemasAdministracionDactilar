<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet"> <!-- Agrega Tailwind CSS -->
  <style>
    body {
      text-align: center;
      background-color: white;
      margin: 0;
      overflow: hidden;
    }

    .center-div {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    h1 {
      font-size: 3rem;
      font-weight: bold;
      text-decoration: underline;
    }

    #ingresar-button {
      background-color: #003366; /* Azul marino */
      color: white;
      padding: 5px 10px; /* Reducir el padding para hacer el botón más pequeño */
      border: none;
      font-size: 1rem; /* Reducir el tamaño de fuente del botón */
      cursor: pointer;
      text-decoration: none;
      border-radius: 20px; /* Agrega bordes redondeados */
      margin-top: 20px; /* Agregar espacio entre la imagen y el botón */
    }

    img {
      max-width: 100%; /* Utilizar el 100% del ancho disponible */
      max-height: 70vh; /* Aumentar la altura de la imagen al 70% de la pantalla */
      width: auto;
      height: auto;
    }
  </style>
</head>
<body>
  <div class="center-div">
    <img src="imagenes/los_alamos_sinfondo.png" alt="Imagen Los Alamos"><br><br>
    @if(isset($mensaje))
      <div class="bg-red-500 text-white p-4 rounded-md">
        {{ $mensaje }}
      </div>
    @endif
    <a href="{{ route('login') }}" id="ingresar-button">Ingresar</a>
  </div>
</body>
</html>