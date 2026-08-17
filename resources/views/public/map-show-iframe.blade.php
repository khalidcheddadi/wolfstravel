<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Listados - Wolves Travel</title>

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

        .map-section {
            position: relative;
            width: 100%;
            background-color: #ffffff;
            padding: 60px 0 80px;
            z-index: 1;
        }

        .map-background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: calc(100% - 130px);
            z-index: -1;
            background: #0a0f19 url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=2070&auto=format&fit=crop') center/cover no-repeat;
            background-blend-mode: overlay;
        }

        .map-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .map-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 40px;
            margin-bottom: 50px;
        }

        .map-visuals {
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

        .map-text-content {
            flex: 1;
            max-width: 520px;
        }

        .map-title {
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

        .map-description {
            font-size: 14.5px;
            color: #ffffffcf;
            line-height: 1.65;
            margin-bottom: 25px;
            direction: ltr;
        }

        .map-description strong {
            color: #ffffff;
            font-weight: 600;
        }

        .btn-outline-light {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            color: #fff;
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
            border-color: #ffffff;
            transform: translateY(-2px);
        }

        .map-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            position: relative;
            z-index: 5;
            margin-top: 30px;
        }

        .map-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .map-card h3 {
            font-size: 22px;
            font-weight: 700;
            color: #0a0f19;
            margin-bottom: 8px;
        }

        .map-card .map-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .map-wrapper {
            width: 100%;
            height: 450px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .map-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
            filter: grayscale(0.2) invert(0.05);
        }

        .map-legend {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 15px 0;
            border-top: 1px solid #e2e8f0;
        }

        .map-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #1e293b;
        }

        .map-legend-item .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .dot-red { background: #ef4444; }
        .dot-blue { background: #3b82f6; }
        .dot-green { background: #22c55e; }

        .map-info-card {
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

        .map-info-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 5px;
        }

        .map-info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            color: #e2e8f0;
            font-size: 14px;
            line-height: 1.6;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .map-info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .map-info-item .icon-circle {
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

        .map-info-item:hover .icon-circle {
            background: #ef4444;
            transform: scale(1.05);
        }

        .map-info-item .info-content {
            flex: 1;
        }

        .map-info-item .info-content strong {
            display: block;
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .map-info-item .info-content span,
        .map-info-item .info-content a {
            color: #cbd5e1;
            font-size: 13.5px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .map-info-item .info-content a:hover {
            color: #ffffff;
        }

        .listings-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            position: relative;
            z-index: 5;
        }

        .listing-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .listing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .listing-card .listing-icon {
            font-size: 28px;
            color: #ef4444;
            margin-bottom: 12px;
        }

        .listing-card h4 {
            font-size: 17px;
            font-weight: 700;
            color: #0a0f19;
            margin-bottom: 5px;
        }

        .listing-card p {
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
        }

        .listing-card .badge {
            display: inline-block;
            background: #ef4444;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            margin-top: 10px;
        }

        @media (max-width: 1024px) {
            .map-top {
                flex-direction: column;
                text-align: center;
            }

            .map-text-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                max-width: 100%;
            }

            .map-description {
                text-align: center;
            }

            .map-grid {
                grid-template-columns: 1fr;
            }

            .map-background-overlay {
                height: calc(100% - 240px);
            }
        }

        @media (max-width: 768px) {
            .map-visuals {
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

            .map-title {
                font-size: 24px;
            }

            .map-card {
                padding: 20px;
            }

            .map-wrapper {
                height: 300px;
            }

            .map-info-card {
                padding: 25px 20px;
            }

            .map-background-overlay {
                height: calc(100% - 360px);
            }

            .listings-section {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .map-title {
                font-size: 20px;
            }

            .map-background-overlay {
                height: calc(100% - 480px);
            }

            .listings-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 400px) {
            .map-background-overlay {
                height: calc(100% - 700px);
            }
        }
    </style>
</head>
<body>

    <section class="map-section">

        <div class="map-background-overlay"></div>

        <div class="map-container">

            <div class="map-top">

                <div class="map-visuals" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">
                    <div class="circle-container">
                        <svg viewBox="0 0 100 100" class="spinning-svg">
                            <defs>
                                <path id="circlePath" d="M 50, 50 m -35, 0 a 35,35 0 1,1 70,0 a 35,35 0 1,1 -70,0" />
                            </defs>
                            <text fill="#ffffff" font-size="9" font-weight="700" letter-spacing="2">
                                <textPath href="#circlePath">
                                    EXPLORA • DESCUBRE • VIAJA •
                                </textPath>
                            </text>
                        </svg>
                    </div>

                    <div class="img-box img-1">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=500&auto=format&fit=crop"
                             alt="Mapa de viajes" loading="lazy">
                    </div>

                    <div class="img-box img-2">
                        <img src="https://images.unsplash.com/photo-1557426272-fc759fdf7a8d?q=80&w=500&auto=format&fit=crop"
                             alt="Destinos" loading="lazy">
                    </div>

                    <svg class="red-squiggle" viewBox="0 0 100 50" fill="none">
                        <path d="M 10 40 Q 25 10, 40 35 T 70 15 T 90 30"
                              stroke="#ef4444" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="map-text-content" data-aos="fade-left" data-aos-duration="800" data-aos-delay="400">
                    <h2 class="map-title">Mapa de Listados</h2>

                    <svg class="red-wave-line" viewBox="0 0 100 15" fill="none">
                        <path d="M2 10 Q 25 2, 50 10 T 98 10"
                              stroke="#ef4444" stroke-width="3.5" stroke-linecap="round"/>
                    </svg>

                    <p class="map-description">
                        <strong>Descubra todos nuestros destinos</strong> en un solo vistazo. Utilice el mapa interactivo para explorar cada ubicación y encontrar el lugar perfecto para su próxima aventura.
                    </p>

                    <a href="#mapa" class="btn-outline-light">
                        <i class="fa-regular fa-compass"></i> Ver mapa
                    </a>
                </div>

            </div>

            <div class="map-grid" id="mapa">

                <div class="map-card" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <h3>Ubicaciones disponibles</h3>
                    <p class="map-subtitle">Haga clic en los marcadores para obtener más información</p>

                    <div class="map-wrapper">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3036.0!2d-3.7038!3d40.4168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDI1JzAwLjAiTiAzwrA0MicxNS4wIlc!5e0!3m2!1sen!2sus!4v1700000000000"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Mapa interactivo de Wolves Travel">
                        </iframe>
                    </div>

                    <div class="map-legend">
                        <span class="map-legend-item"><span class="dot dot-red"></span> Hoteles</span>
                        <span class="map-legend-item"><span class="dot dot-blue"></span> Restaurantes</span>
                        <span class="map-legend-item"><span class="dot dot-green"></span> Lugares de interés</span>
                    </div>
                </div>

                <div class="map-info-card" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
                    <h3>Información útil</h3>

                    <div class="map-info-item">
                        <div class="icon-circle"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="info-content">
                            <strong>Dirección</strong>
                            <span>Madrid, España</span>
                        </div>
                    </div>

                    <div class="map-info-item">
                        <div class="icon-circle"><i class="fa-solid fa-phone"></i></div>
                        <div class="info-content">
                            <strong>Teléfono</strong>
                            <a href="tel:+34900123456">+34 900 123 456</a>
                        </div>
                    </div>

                    <div class="map-info-item">
                        <div class="icon-circle"><i class="fa-solid fa-envelope"></i></div>
                        <div class="info-content">
                            <strong>Correo</strong>
                            <a href="mailto:support@wolfstravel.com">support@wolfstravel.com</a>
                        </div>
                    </div>

                    <div class="map-info-item">
                        <div class="icon-circle"><i class="fa-regular fa-clock"></i></div>
                        <div class="info-content">
                            <strong>Horario</strong>
                            <span>Lun - Vie: 9:00 - 18:00</span>
                        </div>
                    </div>

                    <div class="map-info-item">
                        <div class="icon-circle"><i class="fa-solid fa-globe"></i></div>
                        <div class="info-content">
                            <strong>Web</strong>
                            <a href="#">www.wolfstravel.com</a>
                        </div>
                    </div>

                    <div style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                        <p style="color: #cbd5e1; font-size: 13px; line-height: 1.5;">
                            <i class="fa-regular fa-circle-check" style="color: #4ade80; margin-right: 6px;"></i>
                            Más de 500 destinos disponibles.
                        </p>
                        <p style="color: #cbd5e1; font-size: 13px; line-height: 1.5; margin-top: 8px;">
                            <i class="fa-regular fa-star" style="color: #fbbf24; margin-right: 6px;"></i>
                            Valorado con 4.8/5 por nuestros viajeros.
                        </p>
                    </div>
                </div>

            </div>

            <div class="listings-section" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                <div class="listing-card">
                    <div class="listing-icon"><i class="fa-solid fa-hotel"></i></div>
                    <h4>Hotel Gran Vía</h4>
                    <p>Ubicado en el centro de Madrid, con vistas espectaculares.</p>
                    <span class="badge">Popular</span>
                </div>

                <div class="listing-card">
                    <div class="listing-icon"><i class="fa-solid fa-utensils"></i></div>
                    <h4>Restaurante El Olivo</h4>
                    <p>Cocina mediterránea con productos locales de temporada.</p>
                    <span class="badge">Recomendado</span>
                </div>

                <div class="listing-card">
                    <div class="listing-icon"><i class="fa-solid fa-landmark"></i></div>
                    <h4>Museo del Prado</h4>
                    <p>Una de las pinacotecas más importantes del mundo.</p>
                    <span class="badge">Imperdible</span>
                </div>

                <div class="listing-card">
                    <div class="listing-icon"><i class="fa-solid fa-tree"></i></div>
                    <h4>Parque del Retiro</h4>
                    <p>Un oasis verde en el corazón de la ciudad.</p>
                    <span class="badge">Familiar</span>
                </div>
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