<?php
$msg_feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
    $name = strip_tags(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject_form = strip_tags(trim($_POST['subject']));
    $message = strip_tags(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($subject_form) && !empty($message)) {
        require_once("../efas/ferramenta/PHPMailer/class.phpmailer.php");
        require_once("../efas/ferramenta/PHPMailer/class.smtp.php");

        $mail = new PHPMailer(true);

        try {
            $mail->isMail();
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('secretaria@euripedesbarsanulfo.org.br', 'Site Institucional - Contato');
            $mail->addAddress('secretaria@euripedesbarsanulfo.org.br', 'Secretaria');
            $mail->addReplyTo($email, $name);
            
            $mail->Subject = "Contato pelo Site: " . $subject_form;
            
            $corpo = "<h3>Nova mensagem de contato recebida pelo site institucional</h3>";
            $corpo .= "<p><strong>Nome:</strong> {$name}</p>";
            $corpo .= "<p><strong>E-mail:</strong> {$email}</p>";
            $corpo .= "<p><strong>Assunto:</strong> {$subject_form}</p>";
            $corpo .= "<p><strong>Mensagem:</strong><br>" . nl2br($message) . "</p>";
            
            $mail->msgHTML($corpo);
            
            if ($mail->send()) {
                $msg_feedback = 'success';
            } else {
                $msg_feedback = 'error';
            }
        } catch (Exception $e) {
            $to = 'secretaria@euripedesbarsanulfo.org.br';
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: Site Institucional <secretaria@euripedesbarsanulfo.org.br>\r\n";
            $headers .= "Reply-To: {$name} <{$email}>\r\n";
            
            $mail_subject = "Contato pelo Site: " . $subject_form;
            $corpo = "Nova mensagem de contato recebida pelo site institucional:<br><br>";
            $corpo .= "Nome: {$name}<br>";
            $corpo .= "E-mail: {$email}<br>";
            $corpo .= "Assunto: {$subject_form}<br>";
            $corpo .= "Mensagem:<br>" . nl2br($message);
            
            if (mail($to, $mail_subject, $corpo, $headers)) {
                $msg_feedback = 'success';
            } else {
                $msg_feedback = 'error';
            }
        }
    } else {
        $msg_feedback = 'invalid';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sociedade Espírita Eurípedes Barsanulfo - Várzea Grande, MT</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Bem-vindo à Sociedade Espírita Eurípedes Barsanulfo em Várzea Grande, MT. Conheça nossas atividades doutrinárias, evangelização infantojuvenil e projetos sociais de apoio à comunidade.">
    <meta name="keywords" content="Sociedade Espírita Eurípedes Barsanulfo, Espiritismo Várzea Grande, Casa Espírita MT, Projetos Sociais, Sopa Fraterna, Palestra Espírita, Passe Espírita, Allan Kardec">
    <meta name="author" content="Sociedade Espírita Eurípedes Barsanulfo">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Inline favicon fallback -->
    <link rel="shortcut icon" href="../favicon.ico" type="image/icon" />
</head>
<body>

    <!-- Header / Navigation -->
    <header id="site-header">
        <div class="container nav-container">
            <a href="../" class="nav-brand">
                <img src="../logo-branca0clor.png" alt="Logo Eurípedes Barsanulfo" class="nav-brand-logo">
            </a>
            
            <button class="nav-toggle" id="nav-toggle-btn" aria-label="Menu de Navegação">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <ul class="nav-menu" id="nav-menu-list">
                <li><a href="#inicio" class="nav-link">Início</a></li>
                <li><a href="#sobre" class="nav-link">Quem Somos</a></li>
                <li><a href="#atividades" class="nav-link">Atividades</a></li>
                <li><a href="#projetos" class="nav-link">Projetos Sociais</a></li>
                <li><a href="#contato" class="nav-link">Contato</a></li>
                <li><a href="../efas/?evento=12" class="nav-btn">Inscrições EFAS</a></li>
            </ul>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="container hero-grid">
            <div class="hero-content">
                <div class="hero-badge">Deus, Cristo e Caridade</div>
                <h1 class="hero-title">Acolhimento,<br><span>Luz e Consolo</span> para a sua alma</h1>
                <p class="hero-description">Seja muito bem-vindo(a) à Sociedade Espírita Eurípedes Barsanulfo. <strong>Deus, Cristo e Caridade</strong> é um lema central na Doutrina Espírita e na tradição cristã, representando a base moral e espiritual para a evolução humana. A expressão simboliza a união entre o Criador, o modelo de amor e a prática do bem sem restrições. Nossa casa está de portas abertas para acolher e iluminar.</p>
                <div class="hero-buttons">
                    <a href="#atividades" class="btn-hero-primary">
                        Ver Nossas Atividades
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 13l-7 7-7-7m14-6l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <a href="../efas/?evento=12" class="btn-hero-secondary">Inscrever-se em Eventos</a>
                </div>
            </div>
            
            <div class="hero-image-wrapper">
                <div class="hero-card-promo">
                    <h3 class="hero-card-title">Nossas Atividades</h3>
                    <p class="hero-card-text">Oferecemos apoio espiritual, palestras públicas e ações de assistência social abertas a toda a comunidade de Várzea Grande - MT.</p>
                    <ul class="hero-card-list">
                        <li class="hero-card-item">
                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                            Tratamento Espiritual & Passes
                        </li>
                        <li class="hero-card-item">
                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                            Palestras e Estudos Doutrinários
                        </li>
                        <li class="hero-card-item">
                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                            Evangelização & Assistência Social
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-padding container" id="sobre">
        <div class="about-grid">
            <div>
                <div class="hero-badge" style="background: rgba(30, 58, 138, 0.05); color: var(--primary);">Quem Somos</div>
                <h2 class="about-title">Sociedade Espírita Eurípedes Barsanulfo</h2>
                <p class="about-text">Somos uma associação civil de caráter filantrópico, cultural e religioso, sem fins lucrativos. Nosso propósito central é a prática da caridade e a divulgação da Doutrina Espírita, conforme codificada por Allan Kardec.</p>
                <p class="about-text">Inspirados no exemplo do pioneiro da educação e caridade espírita, Eurípedes Barsanulfo, buscamos oferecer um ambiente fraterno onde as pessoas possam encontrar paz, esclarecimento espiritual e oportunidades para servir ao próximo.</p>
                
                <div class="about-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.053.244-7.5.718V21h15z"></path></svg>
                        </div>
                        <h4 class="highlight-title">Nossa Casa</h4>
                        <p class="highlight-desc">Espaço seguro para preces, estudo sério e assistência espiritual.</p>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"></path></svg>
                        </div>
                        <h4 class="highlight-title">Caridade</h4>
                        <p class="highlight-desc">Projetos de amparo material e moral voltados a famílias necessitadas.</p>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center;">
                <img src="../logo-branca0clor.png" alt="Símbolo Espírita" style="max-width: 320px; margin-top: 20px;">
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <section class="section-padding bg-secondary-section" id="atividades">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Nossas Atividades Doutrinárias</h2>
                <p class="section-subtitle">Oferecemos reuniões públicas, tratamento e grupos de estudos abertos a todos durante toda a semana.</p>
            </div>
            
            <div class="activities-grid">
                <!-- Activity 1: Segunda-feira -->
                <div class="activity-card">
                    <div class="activity-icon icon-blue">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="activity-name">Triagem para Tratamento</h3>
                    <p class="activity-desc">Acolhimento fraterno e triagem inicial recomendada para quem deseja iniciar o tratamento espiritual na casa.</p>
                    <div class="activity-schedule">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Segunda-feira
                    </div>
                </div>

                <!-- Activity 2: Terça-feira -->
                <div class="activity-card">
                    <div class="activity-icon icon-teal">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z"></path></svg>
                    </div>
                    <h3 class="activity-name">Palestra Pública & Passe</h3>
                    <p class="activity-desc">Exposição de temas evangélicos à luz da Doutrina Espírita, seguida da aplicação do passe magnético de refazimento.</p>
                    <div class="activity-schedule">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Terça-feira
                    </div>
                </div>

                <!-- Activity 3: Quarta-feira -->
                <div class="activity-card">
                    <div class="activity-icon icon-blue">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253"></path></svg>
                    </div>
                    <h3 class="activity-name">Tratamento Espiritual & Passe</h3>
                    <p class="activity-desc">Reunião dedicada especificamente para a fluidoterapia e aplicação de passes de tratamento de saúde espiritual.</p>
                    <div class="activity-schedule">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Quarta-feira
                    </div>
                </div>

                <!-- Activity 4: Sábado de Manhã -->
                <div class="activity-card">
                    <div class="activity-icon icon-gold">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.97 5.97 0 00-.75-2.985m-.058-3.128c.99.743 1.847 1.724 2.508 2.875M15.75 8.25a3 3 0 11-6 0 3 3 0 016 0zM6 10.33a3 3 0 004.5-2.59M9 11.622a6 6 0 013-5.243m4.5 5.243a5.986 5.986 0 01-3 5.243m-9.75-1.9L3 18.72a9 9 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.002.031c0 .225-.013.447-.037.666A11.944 11.944 0 016 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 010 18.719m12 0a5.97 5.97 0 00-.75-2.985m-.058-3.128c.99.743 1.847 1.724 2.508 2.875M15.75 8.25a3 3 0 11-6 0 3 3 0 016 0zM6 10.33a3 3 0 004.5-2.59M9 11.622a6 6 0 013-5.243m4.5 5.243a5.986 5.986 0 01-3 5.243m-9.75-1.9"></path></svg>
                    </div>
                    <h3 class="activity-name">Atividades Assistenciais</h3>
                    <p class="activity-desc">Evangelização de crianças, jovens e adultos unida à nossa tradicional preparação e distribuição da Sopa Fraterna.</p>
                    <div class="activity-schedule">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Sábado (Manhã)
                    </div>
                </div>

                <!-- Activity 5: Sábado à Noite -->
                <div class="activity-card">
                    <div class="activity-icon icon-teal">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                    </div>
                    <h3 class="activity-name">Estudos Espíritas Global Kardec</h3>
                    <p class="activity-desc">Escola de estudos profundos e sistematizados dos ensinamentos de Allan Kardec e do Evangelho Redentor.</p>
                    <div class="activity-schedule">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Sábado (Noite)
                    </div>
                </div>

                <!-- Activity 6: Domingo de Manhã -->
                <div class="activity-card">
                    <div class="activity-icon icon-gold">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a3 3 0 00-3 3h3zm0 0H9m4.5 0a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0z"></path></svg>
                    </div>
                    <h3 class="activity-name">Campanhas Fraternas</h3>
                    <p class="activity-desc">Práticas externas de arrecadação e divulgação da fraternidade: Campanha Chico Xavier e Campanha Auta de Souza.</p>
                    <div class="activity-schedule">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Domingo (Manhã)
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section class="section-padding" id="projetos">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Nossos Projetos Sociais</h2>
                <p class="section-subtitle">Promovemos a formação humana, cultural e a educação transformadora de base moral espírita.</p>
            </div>
            
            <div class="projects-grid">
                <!-- Project 1: Projeto Alquimia -->
                <div class="project-card">
                    <div class="project-info">
                        <div class="project-tag" style="background-color: rgba(13, 148, 136, 0.1); color: var(--secondary);">Cultura & Arte</div>
                        <h3 class="project-title">Projeto Alquimia</h3>
                        <p class="project-desc">Um lindo projeto social que atende crianças e jovens no período vespertino. Promovemos o desenvolvimento humano e social através de diversas atividades artísticas, culturais e esportivas: aulas de violão, violino, bateria, aulas de dança, esportes marciais e muito mais.</p>
                        
                        <div class="project-stats">
                            <div class="stat-item">
                                <span class="stat-num">Vespertino</span>
                                <span class="stat-label">Turno de Atendimento</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-num">Múltiplas</span>
                                <span class="stat-label">Oficinas e Instrumentos</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project 2: Educandário Espírita Maria de Nazaré -->
                <div class="project-card">
                    <div class="project-info">
                        <div class="project-tag" style="background-color: rgba(30, 58, 138, 0.1); color: var(--primary);">Educação Integrada</div>
                        <h3 class="project-title">Educandário Espírita Maria de Nazaré</h3>
                        <p class="project-desc">Nossa instituição de ensino voltada ao acolhimento e desenvolvimento acadêmico, moral e social de alunos. Atendemos crianças e adolescentes desde o Pré-Escolar até o 1º ano do Ensino Médio, aliando a formação curricular à ética espírita-cristã.</p>
                        
                        <div class="project-stats">
                            <div class="stat-item">
                                <span class="stat-num">Pré ao 1º ano</span>
                                <span class="stat-label">Ensino Médio</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-num">Pedagogia</span>
                                <span class="stat-label">Base Espírita-Cristã</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action (Volunteer) -->
    <section class="section-padding cta-section">
        <div class="container cta-content">
            <h2 class="cta-title">A caridade é o dever de todos</h2>
            <p class="cta-text">A Sociedade Espírita Eurípedes Barsanulfo vive da dedicação voluntária e de doações de corações generosos. Seja doando mantimentos, auxiliando no preparo das sopas ou atuando nos grupos de apoio, você pode fazer a diferença.</p>
            <div class="cta-buttons">
                <a href="#contato" class="btn-white">Seja um Voluntário</a>
                <a href="../efas/?evento=12" class="btn-outline-white">Inscrições EFAS</a>
            </div>
        </div>
    </section>

    <!-- Contact & Map Section -->
    <section class="section-padding container" id="contato">
        <div class="contact-grid">
            <div class="contact-info">
                <h2 class="info-title">Entre em Contato ou Visite-nos</h2>
                <p class="about-text" style="margin-bottom: 35px;">Ficaremos muito felizes em receber a sua visita. Venha assistir a uma palestra, participar dos estudos ou colaborar com nossos trabalhos voluntários.</p>
                
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path></svg>
                        </div>
                        <div class="info-details">
                            <h4>Endereço</h4>
                            <p>Rua da Primavera, Qd. 68 - Lote</p>
                            <p>Várzea Grande - MT, 78131-178</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path></svg>
                        </div>
                        <div class="info-details">
                            <h4>E-mail</h4>
                            <p>secretaria@euripedesbarsanulfo.org.br</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Message Form -->
            <div class="contact-form-card">
                <h3 class="form-title">Envie uma Mensagem</h3>
                <?php if ($msg_feedback === 'success'): ?>
                    <div style="background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgb(16, 185, 129); color: rgb(16, 185, 129); padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                        Mensagem enviada com sucesso! Em breve entraremos em contato.
                    </div>
                <?php elseif ($msg_feedback === 'error'): ?>
                    <div style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68); color: rgb(239, 68, 68); padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                        Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.
                    </div>
                <?php elseif ($msg_feedback === 'invalid'): ?>
                    <div style="background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgb(245, 158, 11); color: rgb(245, 158, 11); padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                        Por favor, preencha todos os campos do formulário.
                    </div>
                <?php endif; ?>
                <form action="" method="POST">
                    <input type="hidden" name="contact_form" value="1">
                    <div class="form-group">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Seu nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">E-mail de Contato</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="seuemail@exemplo.com" required>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">Assunto</label>
                        <select id="subject" name="subject" class="form-control" required>
                            <option value="">Selecione uma opção...</option>
                            <option value="Dúvidas">Dúvidas Gerais</option>
                            <option value="Voluntariado">Quero ser Voluntário</option>
                            <option value="Doações">Fazer Doações</option>
                            <option value="Atendimento">Atendimento Fraterno</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Mensagem</label>
                        <textarea id="message" name="message" class="form-control" placeholder="Escreva sua mensagem aqui..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container footer-grid">
            <div class="footer-brand">
                <h3>Sociedade Espírita Eurípedes Barsanulfo</h3>
                <p>Promovendo o esclarecimento e o consolo de corações através da Doutrina Espírita e de ações fraternas na comunidade de Várzea Grande - MT.</p>
            </div>
            <div class="footer-links">
                <h4>Acesso Rápido</h4>
                <ul>
                    <li><a href="#inicio">Início</a></li>
                    <li><a href="#sobre">Quem Somos</a></li>
                    <li><a href="#atividades">Atividades</a></li>
                    <li><a href="#projetos">Projetos Sociais</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Portal e Eventos</h4>
                <ul>
                    <li><a href="../efas/?evento=12">Inscrições EFAS</a></li>
                    <li><a href="../efas/inscricao.php?evento=12">Fazer Inscrição</a></li>
                    <li><a href="../efas/consultar_inscricao.php?evento=12">Consultar Inscrição</a></li>
                </ul>
            </div>
        </div>
        <div class="container footer-bottom">
            <p class="footer-copy">&copy; <?php echo date("Y"); ?> Sociedade Espírita Eurípedes Barsanulfo. Todos os direitos reservados.</p>
            <a href="#inicio" class="footer-back-to-top">Voltar ao topo</a>
        </div>
    </footer>

    <!-- Basic Navigation Toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('nav-toggle-btn');
            const menuList = document.getElementById('nav-menu-list');
            const header = document.getElementById('site-header');

            // Toggle Mobile Menu
            toggleBtn.addEventListener('click', function() {
                menuList.classList.toggle('active');
            });

            // Close Mobile Menu on Link click
            const navLinks = document.querySelectorAll('.nav-link, .nav-btn');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    menuList.classList.remove('active');
                });
            });

            // Header Scroll class change
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        });
    </script>

</body>
</html>
