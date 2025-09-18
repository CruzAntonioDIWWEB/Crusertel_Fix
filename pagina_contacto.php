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
        
        // =========================================
        // 🎨 ULTRA FANCY EMAIL NOTIFICATION 🎨
        // =========================================
        
        $titulo = "🚀 ¡NUEVA INTERACCIÓN EN LA PÁGINA WEB! | Crusertel ";
        
        // Crear timestamp con zona horaria española
        date_default_timezone_set('Europe/Madrid');
        $fechaHora = date("d/m/Y") . " a las " . date("H:i");
        $diaSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'][date('w')];
        
        // Generar ID único para el mensaje
        $mensajeId = strtoupper(substr(md5($email . time()), 0, 8));
        
        // ============ VERSIÓN HTML (PREMIUM) ============
        $htmlContent = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Nuevo Contacto - Crusertel</title>
        </head>
        <body style="margin: 0; padding: 0; font-family: Inter, Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
            <table role="presentation" style="width: 100%; max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <!-- HEADER CON GRADIENTE CRUSERTEL -->
                <tr>
                    <td style="background: linear-gradient(135deg, #dc3545 0%, #b1001d 100%); padding: 40px 30px; text-align: center;">
                        <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            🚀 ¡NUEVO CONTACTO!
                        </h1>
                        <p style="color: #ffebee; margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">
                            Crusertel SLU
                        </p>
                        <div style="background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 20px; display: inline-block; margin-top: 15px;">
                            <span style="color: #ffffff; font-size: 14px; font-weight: 500;">ID: #' . $mensajeId . '</span>
                        </div>
                    </td>
                </tr>
                
                <!-- INFORMACIÓN DEL CLIENTE -->
                <tr>
                    <td style="padding: 30px;">
                        <div style="background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%); border-radius: 15px; padding: 25px; margin-bottom: 20px; border-left: 5px solid #dc3545;">
                            <h2 style="color: #333; margin: 0 0 20px 0; font-size: 20px; display: flex; align-items: center;">
                                👤 Información del Cliente
                            </h2>
                            
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #eee;">
                                        <span style="color: #dc3545; font-weight: 600; display: inline-block; width: 80px;">🏷️ Nombre:</span>
                                        <span style="color: #333; font-weight: 500; font-size: 16px;">' . htmlspecialchars($nombre) . '</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #eee;">
                                        <span style="color: #dc3545; font-weight: 600; display: inline-block; width: 80px;">📧 Email:</span>
                                        <a href="mailto:' . htmlspecialchars($email) . '" style="color: #0066cc; text-decoration: none; font-weight: 500;">' . htmlspecialchars($email) . '</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <span style="color: #dc3545; font-weight: 600; display: inline-block; width: 80px;">📝 Asunto:</span>
                                        <span style="color: #333; font-weight: 500; font-size: 16px;">' . htmlspecialchars($asunto) . '</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- MENSAJE -->
                        <div style="background: linear-gradient(135deg, #fff5f5 0%, #ffebee 100%); border-radius: 15px; padding: 25px; border-left: 5px solid #dc3545;">
                            <h3 style="color: #333; margin: 0 0 15px 0; font-size: 18px; display: flex; align-items: center;">
                                Mensaje del Interesado/a
                            </h3>
                            <div style="background: #ffffff; padding: 20px; border-radius: 10px; border: 1px solid #f0f0f0; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                                <p style="color: #444; line-height: 1.6; margin: 0; font-size: 15px; white-space: pre-line;">' . htmlspecialchars($mensaje) . '</p>
                            </div>
                        </div>
                        
                        <!-- BOTÓN DE ACCIÓN -->
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="mailto:' . htmlspecialchars($email) . '?subject=Re: ' . htmlspecialchars($asunto) . '" 
                               style="background: linear-gradient(135deg, #dc3545 0%, #b1001d 100%); 
                                      color: #ffffff; 
                                      padding: 15px 30px; 
                                      border-radius: 50px; 
                                      text-decoration: none; 
                                      font-weight: 600; 
                                      font-size: 16px;
                                      display: inline-block;
                                      box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
                                      transition: all 0.3s ease;">
                                📨 Responder al Correo
                            </a>
                        </div>
                    </td>
                </tr>
                
                <!-- FOOTER CON DETALLES -->
                <tr>
                    <td style="background: #f8f9fa; padding: 25px 30px; border-top: 1px solid #eee;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                            <div>
                                <p style="margin: 0; color: #666; font-size: 14px;">
                                    🌐 <strong>Enviado desde:</strong> ' . $_SERVER['HTTP_HOST'] . '
                                </p>
                                <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">
                                    📅 <strong>' . $diaSemana . ', ' . $fechaHora . '</strong>
                                </p>
                            </div>
                        </div>
                        
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                            <p style="margin: 0; color: #999; font-size: 12px;">
                                Este mensaje fue generado automáticamente por el sistema de contacto de Crusertel
                            </p>
                            <p style="margin: 5px 0 0 0; color: #dc3545; font-size: 13px; font-weight: 600;">
                                💼 Crusertel Telecomunicaciones | Conectamos tu mundo con la mejor señal
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
            
            <!-- SIGNATURE INVISIBLE PARA TRACKING -->
            <div style="font-size: 1px; color: transparent; line-height: 1px;">
                Crusertel-Contact-' . $mensajeId . '-' . time() . '
            </div>
        </body>
        </html>';
        
        // ============ VERSIÓN TEXTO PLANO (BACKUP) ============
        $textoPlano = "
╔══════════════════════════════════════════════════════════════════╗
║                    🚀 ¡NUEVO CONTACTO! 🚀                       ║
║                  CRUSERTEL SLU                                   ║
╠══════════════════════════════════════════════════════════════════╣
║  ID del Mensaje: #" . $mensajeId . "                             ║
╚══════════════════════════════════════════════════════════════════╝

👋 ¡Muy Buenas!

Acabas de recibir un nuevo contacto a través de 
tu formulario web. Aquí tienes los detalles:

┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃                    📋 DETALLES DEL CONTACTO                   ┃
┣━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┫
┃                                                                ┃
┃  👤 NOMBRE:    " . str_pad($nombre, 47) . "                   ┃
┃  📧 EMAIL:     " . str_pad($email, 47) . "                    ┃  
┃  📝 ASUNTO:    " . str_pad($asunto, 47) . "                   ┃
┃                                                                ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛

💬 MENSAJE DEL INTERESADO/A:
" . str_repeat("─", 65) . "
" . $mensaje . "
" . str_repeat("─", 65) . "

" . str_repeat("═", 65) . "
📊 INFORMACIÓN DEL SISTEMA
" . str_repeat("═", 65) . "
🌐 Origen:           " . $_SERVER['HTTP_HOST'] . "
📅 Fecha:            " . $diaSemana . ", " . $fechaHora . " (España)
🔢 ID Seguimiento:   #" . $mensajeId . "
⚡ Estado:           NUEVO MENSAJE
" . str_repeat("═", 65) . "

---
Este mensaje fue generado automáticamente el " . date("d/m/Y H:i:s") . "
Sistema de notificaciones Crusertel v2.0 ⚡
";

        // ============ CONFIGURACIÓN DE HEADERS AVANZADA ============
        $cabeceras = "From: \"Crusertel Notificaciones\" <noreply@crusertel.es>\r\n";
        $cabeceras .= "Reply-To: \"" . htmlspecialchars($nombre) . "\" <" . $email . ">\r\n";
        $cabeceras .= "Return-Path: <noreply@crusertel.es>\r\n";
        $cabeceras .= "X-Mailer: Crusertel Contact System v2.0\r\n";
        $cabeceras .= "X-Priority: 2 (High)\r\n";
        $cabeceras .= "X-MSMail-Priority: High\r\n";
        $cabeceras .= "Importance: High\r\n";
        $cabeceras .= "MIME-Version: 1.0\r\n";
        
        // Email multipart (HTML + texto plano)
        $boundary = "CRUSERTEL_" . md5(time());
        $cabeceras .= "Content-Type: multipart/alternative; boundary=\"" . $boundary . "\"\r\n";
        
        $cuerpoEmail = "--" . $boundary . "\r\n";
        $cuerpoEmail .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $cuerpoEmail .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $cuerpoEmail .= $textoPlano . "\r\n\r\n";
        
        $cuerpoEmail .= "--" . $boundary . "\r\n";
        $cuerpoEmail .= "Content-Type: text/html; charset=UTF-8\r\n";
        $cuerpoEmail .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $cuerpoEmail .= $htmlContent . "\r\n\r\n";
        
        $cuerpoEmail .= "--" . $boundary . "--\r\n";

        // Intentar enviar el email
        $emailEnviado = mail($para, $titulo, $cuerpoEmail, $cabeceras);
        
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