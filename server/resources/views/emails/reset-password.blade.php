<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8">
    <title>Recuperar Contraseña - iCita</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fb;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;

        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 102, 136, 0.05);
            border: 1px solid #e3f2fd;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f4f8;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #006688;
            font-size: 26px;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .content {
            color: #334155;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .button-container {
            text-align: center;
            margin: 35px 0;
        }

        .btn {
            background-color: #0077bd;
            color: #ffffff !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(0, 119, 189, 0.15);
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #f0f4f8;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div style="background-color: #f7f9fb; padding: 40px 0;">
        <div class="container">
            <div class="header">
                <h1>iCita Medical</h1>
            </div>
            <div class="content">
                <p>Estimado usuario,</p>
                <p>Hemos recibido una solicitud para restablecer la contraseña de su cuenta en la plataforma de
                    gestión médica <strong>iCita</strong>.</p>
                <p>Para continuar con el proceso y definir su nueva clave, por favor pulse en el siguiente
                    botón:</p>
                <div class="button-container">
                    <a href="{{ $resetUrl }}" class="btn">Restablecer mi Contraseña</a>
                </div>
                <p><em>Este enlace es de un solo uso y caducará automáticamente por motivos de
                        seguridad.</em></p>
                <p>Si no ha solicitado este cambio, puede ignorar este mensaje; su cuenta permanecerá
                    segura.</p>
            </div>
            <div class="footer">
                <p>Este correo electrónico fue generado automáticamente. Por favor no responda a este
                    mensaje.</p>
                <p>&copy; {{ date('Y') }} iCita. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>


</html>
