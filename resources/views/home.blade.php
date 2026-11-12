<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fffaf7">
    <title>Cardápio — seu menu, do seu jeito</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="landing-page">
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}" aria-label="Cardápio, página inicial">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 32 32" fill="none">
                    <path d="M7 4v9M11 4v9M15 4v9M11 13v15M24 4c-4 4-5 9-5 14h5v10" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span>cardápio<span class="brand-period">.</span></span>
        </a>
        <nav class="header-actions" aria-label="Navegação principal">
            <a class="header-link" href="#como-funciona">Conheça a plataforma</a>
            <a class="header-link account-access-link" href="{{ route('visitor.login') }}">Área do visitante <span aria-hidden="true">→</span></a>
        </nav>
    </header>

    <main class="landing-main">
        <section class="intro-column" aria-labelledby="hero-title">
            <div class="eyebrow"><span class="eyebrow-dot"></span> SEU NEGÓCIO, MAIS DIGITAL</div>
            <h1 id="hero-title">Um cardápio que abre o <span>apetite.</span></h1>
            <p class="hero-copy">Apresente seus produtos com beleza, praticidade e a sua cara. Tudo começa com um link.</p>

            <div class="feature-list" id="como-funciona">
                <div class="feature-item">
                    <span class="feature-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.7 7.5 4.4 7.5-4.4M12 12v8.5" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                    </span>
                    <span><strong>Seu menu sempre atualizado</strong><small>Edite e organize tudo em um só lugar.</small></span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M12 3.5a8.5 8.5 0 0 0-7.4 12.7L3.5 21l4.9-1.1A8.5 8.5 0 1 0 12 3.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M8.8 9.1c.3 2.5 2.5 4.7 5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    </span>
                    <span><strong>Compartilhe sem complicação</strong><small>Um link fácil para seus clientes acessarem.</small></span>
                </div>
            </div>

            <div class="hero-note">
                <span class="note-stars" aria-hidden="true">✳</span>
                <span>Feito para quem serve com carinho.</span>
            </div>
        </section>

        <section class="access-column" aria-label="Acesse sua conta">
            <div class="access-card">
                <livewire:access-card audience="admin" />
            </div>
            <p class="secure-note">
                <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5a3.5 3.5 0 0 1 7 0v2M3 7h10v7H3V7Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Seus dados ficam protegidos e seguros.
            </p>
        </section>
    </main>

    <footer class="site-footer">
        <span>© {{ date('Y') }} Cardápio</span>
        <span>Mais sabor, menos complicação.</span>
    </footer>
    @livewireScripts
</body>
</html>
