<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se enviaron datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos de conexión (ajusta según tu hosting)
    $host = "db5017966651.hosting-data.io";
    $usuario = "dbu80693";
    $contrasena = "Purullena*18519";
    $baseDeDatos = "dbs14291587";

    // Conexión
    $conexion = new mysqli($host, $usuario, $contrasena, $baseDeDatos);

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Obtener y limpiar los datos del formulario
    $nombre = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $asunto = trim($_POST["subject"] ?? '');
    $mensaje = trim($_POST["message"] ?? '');

    // Validar que los campos requeridos no estén vacíos
    if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
        header("Location: pagina_contacto.php?error=" . urlencode("Todos los campos son obligatorios"));
        exit();
    }

    // Consulta para insertar en la tabla 'formularios'
    $sql = "INSERT INTO formularios (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        header("Location: pagina_contacto.php?error=" . urlencode("Error en la base de datos"));
        exit();
    }

    $stmt->bind_param("ssss", $nombre, $email, $asunto, $mensaje);

    if ($stmt->execute()) {
        // Enviar notificación por email SOLO después de guardar correctamente
        $para = "info@crusertel.es";
        $titulo = "Nuevo mensaje del formulario - Crusertel";
        $mensajeCorreo = "Has recibido un nuevo mensaje desde el formulario de contacto:\n\n";
        $mensajeCorreo .= "Nombre: " . $nombre . "\n";
        $mensajeCorreo .= "Email: " . $email . "\n";
        $mensajeCorreo .= "Asunto: " . $asunto . "\n";
        $mensajeCorreo .= "Mensaje:\n" . $mensaje . "\n\n";
        $mensajeCorreo .= "---\n";
        $mensajeCorreo .= "Mensaje enviado desde: " . $_SERVER['HTTP_HOST'];
        
        $cabeceras = "From: noreply@crusertel.es\r\n";
        $cabeceras .= "Reply-To: " . $email . "\r\n";
        $cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Intentar enviar el email
        $emailEnviado = mail($para, $titulo, $mensajeCorreo, $cabeceras);
        
        if ($emailEnviado) {
            header("Location: pagina_contacto.php?success=1");
        } else {
            // Mensaje guardado pero email falló
            header("Location: pagina_contacto.php?success=1&email_warning=1");
        }
    } else {
        header("Location: pagina_contacto.php?error=" . urlencode("Error al guardar el mensaje"));
    }

    $stmt->close();
    $conexion->close();
} else {
    // Si no es POST, redirigir al formulario
    header("Location: pagina_contacto.php");
    exit();
}
?>