<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crusertel - Contáctanos</title>
    <link rel="stylesheet" href="main.css?v=1.1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <section id="contact-intro">
            <div class="container">
                <h2>Contáctanos</h2>
                <p><b>¿Tienes alguna pregunta, sugerencia o necesitas asistencia? No dudes en ponerte en contacto con nosotros.</b></p>
            </div>
        </section>

        <section id="contact-info">
            <div class="contact-cards">
                <div class="contact-card">
                    <h4>📞 Teléfono</h4>
                    <p><a href="tel:+34958016411">958 01 64 11</a></p>
                </div>
                <div class="contact-card">
                    <h4>✉️ Email</h4>
                    <p><a href="mailto:info@crusertel.es">info@crusertel.es</a></p>
                </div>
                <div class="contact-card">
                    <h4>📍 Dirección</h4>
                    <p>Calle Arabial 45 local 18<br>18003 Granada, España</p>
                </div>
            </div>
        </section>

        <section id="contact-form">
            <div class="container">
                <h3>Envíanos un Mensaje</h3>
                <form method="POST" action="guardar_contacto.php">
                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Asunto:</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Mensaje:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn">Enviar Mensaje</button>
                </form>
            </div>
        </section>
    </main>

    <footer class="fade-in-up-initial">
        <div class="container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; font-size: 0.95em;">
            <span>📧 info@crusertel.es</span>
            <span>|</span>
            <span>📍 Calle Arabial 45 local 18</span>
            <span>|</span>
            <span>📞 958 01 64 11</span>
        </div>
    </footer>

    <script src="main.js"></script>
</body>
</html>