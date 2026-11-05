<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#faf8f6">
    <title>Painel administrativo — Cardápio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="dashboard-page">
    <aside class="dashboard-sidebar">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Cardápio, painel">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 32 32" fill="none">
                    <path d="M7 4v9M11 4v9M15 4v9M11 13v15M24 4c-4 4-5 9-5 14h5v10" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span>cardápio<span class="brand-period">.</span></span>
        </a>

        <p class="sidebar-label">MENU PRINCIPAL</p>
        <a class="sidebar-link active" href="{{ route('dashboard') }}">
            <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3" y="3" width="5.5" height="5.5" rx="1.2" stroke="currentColor" stroke-width="1.5"/><rect x="11.5" y="3" width="5.5" height="5.5" rx="1.2" stroke="currentColor" stroke-width="1.5"/><rect x="3" y="11.5" width="5.5" height="5.5" rx="1.2" stroke="currentColor" stroke-width="1.5"/><rect x="11.5" y="11.5" width="5.5" height="5.5" rx="1.2" stroke="currentColor" stroke-width="1.5"/></svg>
            Visão geral
        </a>

        <div class="sidebar-bottom">
            <p class="sidebar-label">SUA CONTA</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="sidebar-link sidebar-link-button" type="submit">
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M8 3H4.5A1.5 1.5 0 0 0 3 4.5v11A1.5 1.5 0 0 0 4.5 17H8M12.5 6.5 16 10l-3.5 3.5M7 10h9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Sair da conta
                </button>
            </form>
        </div>
    </aside>

    <main class="dashboard-main">
        @php($user = auth()->user())
        @php($initial = mb_strtoupper(mb_substr($user->name, 0, 1)))

        <header class="dashboard-topbar">
            <a class="dashboard-visitor-link" href="{{ route('public-menu') }}" target="_blank" rel="noopener">Ver cardápio público <span aria-hidden="true">↗</span></a>
            <span class="user-avatar" aria-hidden="true">{{ $initial }}</span>
            <span class="user-meta">{{ $user->name }}<small>{{ $user->email }}</small></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-button" type="submit">Sair</button>
            </form>
        </header>

        <section class="dashboard-welcome">
            <p class="card-kicker">PAINEL ADMINISTRATIVO</p>
            <h1>Olá, {{ $user->name }} 👋</h1>
            <p>Crie o cardápio do dia e mantenha seus alimentos sempre atualizados para os visitantes.</p>
        </section>

        <section class="stats-grid" aria-label="Resumo do cardápio">
            <article class="stat-card">
                <span>Cardápios criados</span>
                <strong>{{ $menuCount }}</strong>
            </article>
            <article class="stat-card">
                <span>Alimentos cadastrados</span>
                <strong>{{ $foodCount }}</strong>
            </article>
            <article class="stat-card">
                <span>Cardápio de hoje</span>
                <strong class="stat-status {{ $todayMenuExists ? 'is-published' : '' }}">{{ $todayMenuExists ? 'Publicado' : 'Não criado' }}</strong>
            </article>
        </section>

        <livewire:menu-manager />
    </main>
    @livewireScripts
</body>
</html>
