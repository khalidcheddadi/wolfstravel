<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wolfstravel - Confirmación de correo electrónico</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Tajawal', Arial, sans-serif;
            background-color: #f4f7fa;
            -webkit-font-smoothing: antialiased;
            margin: 0;
            padding: 20px 10px;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0,0,0,0.06);
            border: 1px solid #eeeeee;
        }

        .email-header {
            background-color: #001c3d;
            padding: 28px 25px;
            text-align: center;
        }
        .brand-icon {
            font-size: 28px;
            color: #ff4a5a;
            margin-right: 8px;
        }
        .brand-name {
            font-size: 26px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.5px;
            text-decoration: none;
        }

        .email-body {
            padding: 40px 35px 25px 35px;
            background: #ffffff;
        }
        .greeting {
            font-size: 22px;
            font-weight: 800;
            color: #222222;
            margin-bottom: 18px;
        }
        .paragraph {
            font-size: 15px;
            line-height: 1.9;
            color: #444444;
            margin-bottom: 16px;
        }

        .email-icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
        }
        .email-icon-circle i {
            font-size: 28px;
            color: #001c3d;
        }

        .btn-container {
            text-align: center;
            margin: 30px 0 25px 0;
        }
        .btn-primary {
            display: inline-block;
            background-color: #001c3d;
            color: #ffffff !important;
            padding: 14px 40px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            transition: background 0.3s;
            font-family: 'Tajawal', Arial, sans-serif;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 28, 61, 0.25);
        }
        .btn-primary i {
            margin-right: 8px;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #002d62;
        }

        .email-footer {
            background-color: #fbfbfb;
            padding: 18px 35px;
            border-top: 1px solid #e5e5e5;
            text-align: center;
        }
        .footer-text {
            font-size: 12px;
            color: #888888;
            line-height: 1.7;
        }
        .footer-text a {
            color: #001c3d;
            text-decoration: underline;
            word-break: break-all;
        }
        .subcopy {
            font-size: 12px;
            color: #888888;
            margin-top: 12px;
            line-height: 1.7;
            word-break: break-all;
        }

        @media (max-width: 480px) {
            .email-body { padding: 30px 20px; }
            .email-header { padding: 22px 15px; }
            .brand-name { font-size: 22px; }
            .greeting { font-size: 20px; }
            .btn-primary { padding: 12px 30px; font-size: 14px; }
        }
    </style>
</head>
<body>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f7fa; padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 12px 30px rgba(0,0,0,0.06); border:1px solid #eeeeee;">

                    <tr>
                        <td style="background-color:#001c3d; padding:28px 25px; text-align:center;">
                            <i class="fa-solid fa-suitcase-rolling" style="font-size:28px; color:#ff4a5a; margin-right:8px;"></i>
                            <span style="font-size:26px; font-weight:900; color:#ffffff; letter-spacing:-0.5px; text-decoration:none;">Wolfstravel</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:40px 35px 25px 35px; background:#ffffff;">

                            <div style="width:70px; height:70px; border-radius:50%; background:#eff6ff; display:flex; align-items:center; justify-content:center; margin:0 auto 20px auto;">
                                <i class="fa-solid fa-envelope-open-text" style="font-size:28px; color:#001c3d;"></i>
                            </div>

                            <h1 style="font-size:22px; font-weight:800; color:#222222; margin:0 0 18px 0; text-align:center; font-family:'Tajawal', Arial, sans-serif;">
                                @if (!empty($greeting))
                                    {{ $greeting }}
                                @else
                                    @if ($level === 'error')
                                        @lang('¡Ups!')
                                    @else
                                        @lang('¡Hola!')
                                    @endif
                                @endif
                            </h1>

                            @foreach ($introLines as $line)
                                <p style="font-size:15px; line-height:1.9; color:#444444; margin:0 0 16px 0; text-align:center; font-family:'Tajawal', Arial, sans-serif;">
                                    {{ $line }}
                                </p>
                            @endforeach

                            @isset($actionText)
                                <div style="text-align:center; margin:30px 0 25px 0;">
                                    <a href="{{ $actionUrl }}" style="display:inline-block; background-color:#001c3d; color:#ffffff !important; padding:14px 40px; border-radius:40px; text-decoration:none; font-weight:700; font-size:16px; box-shadow:0 4px 12px rgba(0,28,61,0.25); font-family:'Tajawal', Arial, sans-serif; border:none;">
                                        <i class="fa-solid fa-paper-plane" style="margin-right:8px;"></i> {{ $actionText }}
                                    </a>
                                </div>
                            @endisset

                            @foreach ($outroLines as $line)
                                <p style="font-size:15px; line-height:1.9; color:#444444; margin:0 0 16px 0; text-align:center; font-family:'Tajawal', Arial, sans-serif;">
                                    {{ $line }}
                                </p>
                            @endforeach

                            <p style="font-size:15px; line-height:1.9; color:#444444; margin:25px 0 0 0; text-align:center; font-family:'Tajawal', Arial, sans-serif;">
                                @if (!empty($salutation))
                                    {{ $salutation }}
                                @else
                                    @lang('Saludos,')<br>
                                    <span style="font-weight:700; color:#001c3d;">Wolfstravel</span>
                                @endif
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="background-color:#fbfbfb; padding:18px 35px; border-top:1px solid #e5e5e5; text-align:center;">
                            <p style="font-size:12px; color:#888888; margin:0 0 8px 0; line-height:1.7; font-family:'Tajawal', Arial, sans-serif;">
                                &copy; {{ date('Y') }} <span style="font-weight:700; color:#001c3d;">Wolfstravel</span>. Todos los derechos reservados.
                            </p>

                            @isset($actionText)
                                <p style="font-size:12px; color:#888888; margin:12px 0 0 0; line-height:1.7; word-break:break-all; font-family:'Tajawal', Arial, sans-serif;">
                                    @lang(
                                        "Si tiene problemas para hacer clic en el botón \":actionText\", copie y pegue la siguiente URL en su navegador:",
                                        ['actionText' => $actionText]
                                    )
                                    <br>
                                    <a href="{{ $actionUrl }}" style="color:#001c3d; text-decoration:underline; word-break:break-all;">
                                        {{ $actionUrl }}
                                    </a>
                                </p>
                            @endisset
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>