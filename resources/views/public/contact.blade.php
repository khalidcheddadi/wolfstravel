<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Wolfstravel for travel questions, reservations, support, and custom recommendations. We reply quickly with expert help.">
    <meta name="robots" content="index,follow">
    <meta property="og:title" content="Contact Wolfstravel">
    <meta property="og:description" content="Get in touch with Wolfstravel for travel support, booking questions, and tailored adventures.">
    <meta property="og:image" content="{{ url('/images/logo.png') }}">
    <title>Contáctenos - wolfstravel</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-180x180.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            min-height: 100vh;
            background: #0a0f19;
            font-family: 'Poppins', sans-serif;
        }

        .contact-section {
            position: relative;
            width: 100%;
            background-color: #ffffff;
            padding: 60px 0 80px;
            z-index: 1;
        }

        .contact-background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: calc(100% - 130px);
            z-index: -1;
            background: #0a0f19 url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=2070&auto=format&fit=crop') center/cover no-repeat;
            background-blend-mode: overlay;
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .contact-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 40px;
            margin-bottom: 50px;
        }

        .contact-visuals {
            position: relative;
            width: 420px;
            height: 280px;
            flex-shrink: 0;
        }

        .circle-container {
            position: absolute;
            top: -10px;
            left: -10px;
            width: 110px;
            height: 110px;
            z-index: 10;
        }

        .spinning-svg {
            width: 100%;
            height: 100%;
            animation: rotateText 12s linear infinite;
            filter: drop-shadow(0px 3px 5px rgba(0,0,0,0.4));
        }

        @keyframes rotateText {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .img-box {
            position: absolute;
            border: 3px solid #ffffff;
            border-radius: 0px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }

        .img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .img-1 {
            width: 200px;
            height: 240px;
            top: 30px;
            left: 45px;
            z-index: 2;
        }

        .img-2 {
            width: 180px;
            height: 180px;
            top: 0;
            right: 25px;
            z-index: 1;
        }

        .red-squiggle {
            position: absolute;
            bottom: -10px;
            right: 40px;
            width: 75px;
            z-index: 3;
        }

        .contact-text-content {
            flex: 1;
            max-width: 520px;
        }

        .contact-title {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.3;
            color: #ffffff;
            margin-bottom: 8px;
            direction: ltr;
        }

        .red-wave-line {
            width: 75px;
            height: 10px;
            margin-bottom: 20px;
        }

        .contact-text {
            font-size: 14.5px;
            color: #ffffffcf;
            line-height: 1.65;
            margin-bottom: 25px;
            direction: ltr;
        }

        .contact-text strong {
            color: #ffffff;
            font-weight: 600;
        }

        .listing-map-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #ef4444;
            color: #fff;
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .listing-map-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            position: relative;
            z-index: 5;
            margin-top: 30px;
        }

        .contact-form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px 35px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .contact-form-card h3 {
            font-size: 22px;
            font-weight: 700;
            color: #0a0f19;
            margin-bottom: 8px;
        }

        .contact-form-card .form-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .form-group label .required {
            color: #ef4444;
            margin-left: 4px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            color: #1e293b;
            background: #ffffff;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #001c3d;
            box-shadow: 0 0 0 3px rgba(0, 28, 61, 0.1);
        }

        .form-group textarea {
            min-height: 130px;
            resize: vertical;
        }

        .form-group .error-text {
            color: #ef4444;
            font-size: 13px;
            margin-top: 4px;
            display: block;
        }

        .submit-btn {
            background-color: #001c3d;
            color: #ffffff;
            padding: 14px 35px;
            border: none;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 28, 61, 0.3);
        }

        .submit-btn:hover {
            background-color: #002d62;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 28, 61, 0.4);
        }

        .submit-btn i {
            transition: transform 0.3s ease;
        }

        .submit-btn:hover i {
            transform: translateX(5px);
        }

        .contact-info-card {
            background: rgba(10, 15, 25, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 35px 30px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .contact-info-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 5px;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            color: #e2e8f0;
            font-size: 14px;
            line-height: 1.6;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .contact-info-item:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }

        .contact-info-item .icon-circle {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #ffffff;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .contact-info-item:hover .icon-circle {
            background: #ef4444;
            transform: scale(1.05);
        }

        .contact-info-item .info-content {
            flex: 1;
        }

        .contact-info-item .info-content strong {
            display: block;
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .contact-info-item .info-content span,
        .contact-info-item .info-content a {
            color: #cbd5e1;
            font-size: 13.5px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-info-item .info-content a:hover {
            color: #ffffff;
        }

        .social-links-section {
            margin-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
        }

        .social-links-section h4 {
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .social-icons-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .social-icon-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .social-icon-link:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .social-icon-link.fb:hover { background: #3b5998; }
        .social-icon-link.ig:hover { background: #e4405f; }
        .social-icon-link.yt:hover { background: #ff0000; }
        .social-icon-link.wa:hover { background: #25D366; }

        .social-icon-link i {
            transition: transform 0.3s ease;
        }

        .social-icon-link:hover i {
            transform: scale(1.2);
        }

        .contact-map-wrapper {
            margin-top: 40px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 5;
        }

        .contact-map-wrapper iframe {
            width: 100%;
            height: 300px;
            display: block;
            border: none;
            filter: grayscale(0.2) invert(0.05);
        }

        @media (max-width: 1024px) {
            .contact-top {
                flex-direction: column;
                text-align: center;
            }

            .contact-text-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                max-width: 100%;
            }

            .contact-text {
                text-align: center;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .contact-background-overlay {
                height: calc(100% - 240px);
            }
        }

        @media (max-width: 768px) {
            .contact-visuals {
                width: 100%;
                max-width: 340px;
                height: 240px;
            }

            .img-1 {
                left: 10px;
                width: 58%;
                height: 200px;
            }

            .img-2 {
                right: 0;
                width: 48%;
                height: 160px;
            }

            .contact-title {
                font-size: 24px;
            }

            .contact-form-card {
                padding: 25px 20px;
            }

            .contact-info-card {
                padding: 25px 20px;
            }

            .contact-background-overlay {
                height: calc(100% - 360px);
            }
        }

        @media (max-width: 576px) {
            .contact-title {
                font-size: 20px;
            }

            .contact-background-overlay {
                height: calc(100% - 480px);
            }

            .contact-map-wrapper iframe {
                height: 200px;
            }

            .submit-btn {
                width: 100%;
                justify-content: center;
            }

            .social-icons-row {
                justify-content: center;
            }
        }

        @media (max-width: 400px) {
            .contact-background-overlay {
                height: calc(100% - 700px);
            }
        }
    </style>
</head>
<body>

    <section class="contact-section">

        <div class="contact-background-overlay"></div>

        <div class="contact-container">

            <div class="contact-top">

                <div class="contact-visuals" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">
                    <div class="circle-container">
                        <svg viewBox="0 0 100 100" class="spinning-svg">
                            <defs>
                                <path id="circlePath" d="M 50, 50 m -35, 0 a 35,35 0 1,1 70,0 a 35,35 0 1,1 -70,0" />
                            </defs>
                            <text fill="#ffffff" font-size="9" font-weight="700" letter-spacing="2">
                                <textPath href="#circlePath">
                                    CONTÁCTENOS • ESCRIBANOS •
                                </textPath>
                            </text>
                        </svg>
                    </div>

                    <div class="img-box img-1">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=500&auto=format&fit=crop"
                             alt="Atención al cliente" loading="lazy">
                    </div>

                    <div class="img-box img-2">
                        <img src="https://images.unsplash.com/photo-1557426272-fc759fdf7a8d?q=80&w=500&auto=format&fit=crop"
                             alt="Equipo de trabajo" loading="lazy">
                    </div>

                    <svg class="red-squiggle" viewBox="0 0 100 50" fill="none">
                        <path d="M 10 40 Q 25 10, 40 35 T 70 15 T 90 30"
                              stroke="#ef4444" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="contact-text-content" data-aos="fade-left" data-aos-duration="800" data-aos-delay="400">
                    <h2 class="contact-title">Contáctenos</h2>

                    <svg class="red-wave-line" viewBox="0 0 100 15" fill="none">
                        <path d="M2 10 Q 25 2, 50 10 T 98 10"
                              stroke="#ef4444" stroke-width="3.5" stroke-linecap="round"/>
                    </svg>

                    <p class="contact-text">
                        <strong>El equipo de wolfstravel</strong> está aquí para ayudarle. Ya sea que tenga una consulta sobre una reserva, necesite soporte técnico o desee hacer una sugerencia, no dude en ponerse en contacto con nosotros. Respondemos en un plazo de 24 horas.
                    </p>

                    <a href="mailto:support@wolfstravel.com" class="listing-map-btn">
                        <i class="fa-regular fa-envelope"></i> Envíenos un correo
                    </a>
                </div>

            </div>

            <div class="contact-grid">

                <div class="contact-form-card" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <h3>Envíenos un mensaje</h3>
                    <p class="form-subtitle">¡Estamos aquí para ayudarle! Rellene el formulario y le responderemos lo antes posible.</p>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" style="background: #d1fae5; border-color: #34d399; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" style="background: #fee2e2; border-color: #f87171; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" style="background: #fee2e2; border-color: #f87171; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                            <ul style="margin: 0; padding-left: 18px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Nombre completo <span class="required">*</span></label>
                            <input type="text" name="name" id="name" placeholder="Introduzca su nombre completo" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo electrónico <span class="required">*</span></label>
                            <input type="email" name="email" id="email" placeholder="ejemplo@dominio.com" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="subject">Asunto</label>
                            <input type="text" name="subject" id="subject" placeholder="Asunto de su mensaje" value="{{ old('subject') }}">
                        </div>

                        <div class="form-group">
                            <label for="message">Mensaje <span class="required">*</span></label>
                            <textarea name="message" id="message" placeholder="Escriba su mensaje aquí..." required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="submit-btn">
                            Enviar <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>

                <div class="contact-info-card" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
                    <h3>Información de contacto</h3>

                    <div class="contact-info-item">
                        <div class="icon-circle"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="info-content">
                            <strong>Dirección</strong>
                            <span>Madrid, España</span>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="icon-circle"><i class="fa-solid fa-phone"></i></div>
                        <div class="info-content">
                            <strong>Teléfono</strong>
                            <a href="tel:+34641322118"></a>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="icon-circle"><i class="fa-solid fa-envelope"></i></div>
                        <div class="info-content">
                            <strong>Correo electrónico</strong>
                            <a href="mailto:support@wolfstravel.com">support@wolfstravel.com</a>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="icon-circle"><i class="fa-regular fa-clock"></i></div>
                        <div class="info-content">
                            <strong>Horario de atención</strong>
                            <span>Lunes - Viernes: 9:00 - 18:00</span>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="icon-circle"><i class="fa-brands fa-whatsapp"></i></div>
                        <div class="info-content">
                            <strong>WhatsApp</strong>
                            <a href="https://wa.me/34641322118" target="_blank" rel="noopener noreferrer"></a>
                        </div>
                    </div>

                    <div class="social-links-section">
                        <h4>Síguenos en redes</h4>
                        <div class="social-icons-row">
                            <a href="https://web.facebook.com/profile.php?id=61592900768978" class="social-icon-link fb" target="_blank" rel="noopener noreferrer" title="Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/wolfstravel/" class="social-icon-link ig" target="_blank" rel="noopener noreferrer" title="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="https://www.youtube.com/channel/UCAM-10MVM-Xhi5KN-Z-k-jA" class="social-icon-link yt" target="_blank" rel="noopener noreferrer" title="YouTube">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                            <a href="https://wa.me/34641322118" class="social-icon-link wa" target="_blank" rel="noopener noreferrer" title="WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="contact-map-wrapper" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3036.0!2d-3.7038!3d40.4168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDI1JzAwLjAiTiAzwrA0MicxNS4wIlc!5e0!3m2!1sen!2sus!4v1700000000000"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Mapa de wolfstravel">
                </iframe>
            </div>

        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
