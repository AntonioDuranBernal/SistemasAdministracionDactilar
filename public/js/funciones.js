var card; // Declarar la variable card fuera de las funciones para que sea accesible en ambas funciones
var timer; // Declarar la variable del temporizador

function mostrarCard() {
    card = document.getElementById('miCard');
    card.classList.remove('hidden');

    // Iniciar el temporizador de 5 segundos
    timer = setTimeout(function() {
        cerrarYMostrarMensaje(false); // false indica que no se detectó movimiento
    }, 9000);
}

function cerrarCard() {
    clearTimeout(timer); // Limpiar el temporizador si se cierra el card antes de tiempo
    card.classList.add('hidden');
}

function cerrarYMostrarMensaje(movimientoDetectado) {
    cerrarCard();

    // Verificar si se detectó movimiento dentro de los primeros 5 segundos
    if (movimientoDetectado) {
        mostrarMensaje("Lectura realizada");
    } else {
        mostrarMensaje("Huella no detectada. Por favor, inténtalo nuevamente.");
    }

    // Eliminar el event listener después de mostrar el mensaje
    document.removeEventListener('mousemove', verificarMovimiento);
}

function mostrarMensaje(mensaje) {
    // Muestra el mensaje (puedes personalizar esto según tus necesidades)
    alert(mensaje);
}

// Función para verificar el movimiento del mouse
function verificarMovimiento() {
    cerrarYMostrarMensaje(true); // true indica que se detectó movimiento
}

// Agregar un event listener para detectar el movimiento del mouse
document.addEventListener('mousemove', verificarMovimiento);

// Agregar un event listener al botón "Capturar Huella"
var botonCapturarHuella = document.getElementById('botonCapturarHuella');
botonCapturarHuella.addEventListener('click', function() {
    // Volver a agregar el event listener cuando se hace clic en el botón
    document.addEventListener('mousemove', verificarMovimiento);
    mostrarCard();
});
