<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tu invitación a FaltaUno</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f6f2;font-family:Arial,Helvetica,sans-serif;color:#26352b;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f3f6f2;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;background-color:#ffffff;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td align="center" style="background-color:#2d5a27;padding:28px 24px;">
                            <img src="{{ asset('images/logos/logosmall.png') }}" width="72" height="72" alt="FaltaUno" style="display:block;width:72px;height:72px;border:0;border-radius:12px;">
                            <p style="margin:12px 0 0;color:#ffffff;font-size:15px;letter-spacing:1px;font-weight:bold;">FALTAUNO</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 36px 20px;">
                            <p style="margin:0 0 12px;color:#4a7c59;font-size:13px;font-weight:bold;text-transform:uppercase;letter-spacing:1px;">Invitación de dueño</p>
                            <h1 style="margin:0 0 20px;color:#2d5a27;font-size:26px;line-height:1.3;">¡Hola, {{ $firstName }}! Te damos la bienvenida</h1>
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">Te invitamos a administrar tu complejo en <strong>FaltaUno</strong>. Desde tu cuenta vas a poder gestionar la información y las publicaciones de tus canchas y consultar sus reservas.</p>
                            <p style="margin:0 0 24px;font-size:16px;line-height:1.6;">Para empezar, creá tu contraseña personal desde el botón. Nadie de FaltaUno configuró ni conoce una contraseña para tu cuenta.</p>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto 24px;">
                                <tr>
                                    <td align="center" bgcolor="#ff8c42" style="border-radius:8px;">
                                        <a href="{{ $url }}" style="display:inline-block;padding:15px 26px;color:#ffffff;font-size:16px;font-weight:bold;text-decoration:none;border-radius:8px;">Activar mi cuenta y elegir contraseña</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 12px;font-size:14px;line-height:1.6;color:#59665d;">Por seguridad, el enlace vence en {{ $expiresIn }} minutos y solo se puede usar una vez. Si vence, pedile al administrador que te reenvíe la invitación.</p>
                            <div style="margin:24px 0;padding:16px;background-color:#f0f8f0;border-left:4px solid #4a7c59;border-radius:4px;">
                                <p style="margin:0;font-size:14px;line-height:1.6;"><strong>Tu prueba gratuita:</strong> dura un mes y comienza desde la fecha en que se creó tu cuenta.</p>
                            </div>
                            <p style="margin:0 0 8px;font-size:13px;line-height:1.6;color:#59665d;">Si el botón no funciona, copiá y pegá este enlace en tu navegador:</p>
                            <p style="margin:0 0 24px;font-size:13px;line-height:1.6;word-break:break-all;"><a href="{{ $url }}" style="color:#2d5a27;">{{ $url }}</a></p>
                            <p style="margin:0;font-size:14px;line-height:1.6;">¡Nos alegra que te sumes!<br><strong>El equipo de FaltaUno</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:18px 24px;background-color:#f8faf7;border-top:1px solid #e8eee7;">
                            <p style="margin:0;color:#718075;font-size:12px;line-height:1.6;">Este mensaje fue enviado porque se creó una cuenta de dueño para este correo. Si no esperabas esta invitación, podés ignorarlo.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
