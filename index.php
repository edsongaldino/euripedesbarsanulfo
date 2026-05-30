<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sociedade Espírita Eurípedes Barsanulfo</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Portal de Inscrições e Site Institucional da Sociedade Espírita Eurípedes Barsanulfo. Conheça nossas atividades, projetos sociais e participe de nossos eventos.">
    <meta name="keywords" content="Sociedade Espírita, Eurípedes Barsanulfo, Espiritismo, Eventos Espíritas, EFAS, Inscrição Online, Caridade, Projetos Sociais">
    <meta name="author" content="Sociedade Espírita Eurípedes Barsanulfo">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:title" content="Sociedade Espírita Eurípedes Barsanulfo">
    <meta property="og:description" content="Portal de Inscrições e Site Institucional. Participe de nossos eventos e conheça nossos projetos sociais.">
    <meta property="og:image" content="logo-branca0clor.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="Sociedade Espírita Eurípedes Barsanulfo">
    <meta property="twitter:description" content="Portal de Inscrições e Site Institucional. Participe de nossos eventos e conheça nossos projetos sociais.">
    <meta property="twitter:image" content="logo-branca0clor.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #58c5c9;
            --primary-hover: #45b1b5;
            --primary-glow: rgba(88, 197, 201, 0.4);
            --secondary: #e2b45c;
            --secondary-hover: #cf9f47;
            --secondary-glow: rgba(226, 180, 92, 0.4);
            --bg-start: #0a0f1d;
            --bg-mid: #131130;
            --bg-end: #200f2e;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --font: 'Outfit', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font);
            background: linear-gradient(135deg, var(--bg-start) 0%, var(--bg-mid) 50%, var(--bg-end) 100%);
            background-size: 400% 400%;
            animation: gradientBg 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            overflow-x: hidden;
            padding: 20px;
        }

        @keyframes gradientBg {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Ambient floating lights */
        .ambient-light {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(120px);
            z-index: 1;
            opacity: 0.15;
            pointer-events: none;
        }

        .light-1 {
            top: 10%;
            left: 15%;
            background: var(--primary);
            animation: floatLight1 20s infinite alternate;
        }

        .light-2 {
            bottom: 10%;
            right: 15%;
            background: var(--secondary);
            animation: floatLight2 20s infinite alternate;
        }

        @keyframes floatLight1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 80px) scale(1.2); }
        }

        @keyframes floatLight2 {
            0% { transform: translate(0, 0) scale(1.2); }
            100% { transform: translate(-60px, -50px) scale(0.9); }
        }

        /* Container Card */
        .gateway-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 28px;
            padding: 50px 40px;
            width: 100%;
            max-width: 520px;
            text-align: center;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            z-index: 10;
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeInUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Logo Area */
        .logo-container {
            margin-bottom: 35px;
            position: relative;
            display: inline-block;
        }

        .logo-img {
            max-width: 240px;
            height: auto;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.3));
            transition: transform 0.4s ease;
        }

        .logo-container:hover .logo-img {
            transform: scale(1.03);
        }

        /* Typography */
        h1 {
            font-size: 2rem;
            font-weight: 600;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
            color: #ffffff;
            background: linear-gradient(120deg, #ffffff 60%, rgba(255, 255, 255, 0.7));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 40px;
            line-height: 1.6;
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
            font-weight: 300;
        }

        /* Action Buttons */
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 28px;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            letter-spacing: 0.2px;
            overflow: hidden;
        }

        /* Button Glow & Shimmer effect */
        .btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 30%,
                rgba(255, 255, 255, 0.2) 50%,
                rgba(255, 255, 255, 0.1) 70%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: skewX(-25deg);
            transition: 0.75s;
            opacity: 0;
        }

        .btn:hover::after {
            animation: shine 1.5s infinite;
            opacity: 1;
        }

        @keyframes shine {
            0% { left: -50%; }
            100% { left: 125%; }
        }

        /* Primary style (Registration) */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #3ca0a4 100%);
            color: #071013;
            font-weight: 600;
            box-shadow: 0 8px 20px -4px var(--primary-glow);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px -2px var(--primary-glow);
            background: linear-gradient(135deg, var(--primary-hover) 0%, #328f93 100%);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        /* Secondary style (Institutional) */
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--secondary);
            color: var(--secondary);
            transform: translateY(-3px);
            box-shadow: 0 12px 28px -4px var(--secondary-glow);
        }

        .btn-secondary:active {
            transform: translateY(-1px);
        }

        /* Arrow SVG animation */
        .btn svg {
            transition: transform 0.3s ease;
            width: 18px;
            height: 18px;
        }

        .btn:hover svg {
            transform: translateX(4px);
        }

        /* Footer */
        .footer-text {
            margin-top: 40px;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.3);
            font-weight: 300;
            letter-spacing: 0.5px;
        }

        /* Mobile Adjustments */
        @media (max-width: 480px) {
            .gateway-card {
                padding: 40px 24px;
                border-radius: 20px;
            }

            h1 {
                font-size: 1.6rem;
            }

            .logo-img {
                max-width: 180px;
            }

            .btn {
                padding: 14px 20px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient background light effects -->
    <div class="ambient-light light-1"></div>
    <div class="ambient-light light-2"></div>

    <!-- Main Welcome Card -->
    <main class="gateway-card" id="gateway-card">
        <div class="logo-container">
            <img src="logo-branca0clor.png" alt="Logo Sociedade Espírita Eurípedes Barsanulfo" class="logo-img">
        </div>

        <h1>Sociedade Espírita<br>Eurípedes Barsanulfo</h1>
        <p class="subtitle">Seja bem-vindo(a) à nossa casa. Escolha uma das opções abaixo para acessar nossos canais digitais.</p>

        <div class="button-group">
            <a href="efas/?evento=12" class="btn btn-primary" id="btn-inscricao">
                Inscrições EFAS
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                </svg>
            </a>
            
            <a href="institucional/" class="btn btn-secondary" id="btn-institucional">
                Conhecer a Sociedade Espírita
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                </svg>
            </a>
        </div>

        <p class="footer-text">&copy; <?php echo date("Y"); ?> Sociedade Espírita Eurípedes Barsanulfo. Todos os direitos reservados.</p>
    </main>

</body>
</html>
