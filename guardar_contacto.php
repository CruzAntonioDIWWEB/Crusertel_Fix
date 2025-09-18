<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Datos de conexión (ajusta según tu hosting)
$host = "db5017966651.hosting-data.io";            // o el host que te dé tu proveedor
$usuario = "dbu80693";        // cambia esto
$contrasena = "Purullena*18519";  // cambia esto
$baseDeDatos = "dbs14291587";  // cambia esto

// Conexión
$conexion = new mysqli($host, $usuario, $contrasena, $baseDeDatos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si se enviaron datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["name"] ?? '';
    $email = $_POST["email"] ?? '';
    $asunto = $_POST["subject"] ?? '';
    $mensaje = $_POST["message"] ?? '';

    // Consulta para insertar en la tabla 'formularios'
    $sql = "INSERT INTO formularios (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error en prepare(): " . $conexion->error);
    }

    $stmt->bind_param("ssss", $nombre, $email, $asunto, $mensaje);

    if ($stmt->execute()) {
        echo "Mensaje guardado correctamente en la tabla formularios.";
    } else {
        echo "Error al guardar: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "No se ha enviado el formulario correctamente.";
}
// Enviar notificación por email
$para = "info@crusertel.es";  // ← Cambia esto por tu email real
$titulo = "Nuevo mensaje del formulario";
$mensajeCorreo = "Has recibido un nuevo mensaje:\n\nNombre: $nombre\nEmail: $email\nAsunto: $asunto\nMensaje:\n$mensaje";
$cabeceras = "From: noreply@crusertel.es";  // Usa un dominio real si tienes

mail($para, $titulo, $mensajeCorreo, $cabeceras);
?>