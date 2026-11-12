<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fffaf7">
    <title>{{ $menu?->title ?? 'Cardápio do dia' }} — Cardápio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-menu-page">
    <header class="public-menu-header">
        <a class="brand" href="{{ route('visitor.menu') }}" aria-label="Cardápio do dia">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 32 32" fill="none">
                    <path d="M7 4v9M11 4v9M15 4v9M11 13v15M24 4c-4 4-5 9-5 14h5v10" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span>cardápio<span class="brand-period">.</span></span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-button" type="submit">Sair</button>
        </form>
    </header>

    <main class="public-menu-main">
        <div class="public-menu-intro">
            <p class="card-kicker">FEITO FRESQUINHO PARA VOCÊ</p>
            <h1>Cardápio do dia</h1>
            <p>{{ now()->format('d/m/Y') }} <span class="menu-date-dot"></span> Confira as opções de hoje</p>
        </div>

        @if ($menu)
            <section class="public-menu-card" aria-labelledby="public-menu-title">
                <header class="public-menu-card-header">
                    <span class="menu-day-icon" aria-hidden="true">✳</span>
                    <div>
                        <h2 id="public-menu-title">{{ $menu->title }}</h2>
                        @if ($menu->description)
                            <p>{{ $menu->description }}</p>
                        @endif
                    </div>
                    <span class="menu-today-badge">HOJE</span>
                </header>

                @if ($menu->items->isNotEmpty())
                    <ul class="public-food-list">
                        @foreach ($menu->items as $item)
                            <li class="public-food-item">
                                <span class="food-row-icon" aria-hidden="true">✳</span>
                                <span class="food-row-copy">
                                    <strong>{{ $item->name }}</strong>
                                    @if ($item->description)
                                        <small>{{ $item->description }}</small>
                                    @endif
                                </span>
                                @if ($item->price !== null)
                                    <span class="public-food-price">R$ {{ number_format((float) $item->price, 2, ',', '.') }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="public-menu-empty">O cardápio está sendo preparado. Volte em breve para conferir os alimentos de hoje.</p>
                @endif
            </section>
        @else
            <section class="public-menu-empty-card">
                <span class="empty-menu-icon" aria-hidden="true">
                    <svg viewBox="0 0 28 28" fill="none"><path d="M5 8h18M7 8v14h14V8M11 8V5h6v3M11 13h6M11 17h4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <h2>O cardápio de hoje ainda não foi publicado</h2>
                <p>Estamos preparando algo gostoso. Volte mais tarde para conferir as opções.</p>
            </section>
        @endif

        <aside class="pix-demo-card" aria-label="Chave Pix de demonstração">
            <span class="pix-demo-icon" aria-hidden="true">PIX</span>
            <div class="pix-demo-content">
                <span class="pix-demo-label">CHAVE PIX CPF · DEMONSTRAÇÃO</span>
                <strong>000.000.000-00</strong>
                <p>Este CPF é fictício e não recebe pagamentos. Para aceitar pagamentos, cadastre o CPF vinculado à sua conta Pix.</p>
            </div>
        </aside>

    </main>

    <footer class="public-menu-footer">© {{ date('Y') }} Cardápio <span>Mais sabor, menos complicação.</span></footer>
</body>
</html>
