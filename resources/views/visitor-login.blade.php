<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fffaf7">
    <title>Acesso do visitante — Cardápio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="landing-page">
    <header class="site-header">
        <a class="brand" href="{{ route('visitor.login') }}" aria-label="Cardápio, acesso do visitante">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 32 32" fill="none">
                    <path d="M7 4v9M11 4v9M15 4v9M11 13v15M24 4c-4 4-5 9-5 14h5v10" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span>cardápio<span class="brand-period">.</span></span>
        </a>
        <a class="header-link account-access-link" href="{{ route('home') }}">Acesso do administrador <span aria-hidden="true">→</span></a>
    </header>

    <main class="landing-main">
        <section class="intro-column">
            <div class="eyebrow"><span class="eyebrow-dot"></span> ÁREA DO VISITANTE</div>
            <h1>Seu próximo prato está <span>aqui.</span></h1>
            <p class="hero-copy">Entre ou crie sua conta para conferir o cardápio publicado pelo administrador.</p>
        </section>

        <section class="access-column" aria-label="Acesse sua conta de visitante">
            <div class="access-card">
                @if (session('status'))
                    <p class="menu-notice" role="status">{{ session('status') }}</p>
                @endif
                <livewire:access-card audience="visitor" />
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
