<div>
    <div class="card-heading">
        <p class="card-kicker">{{ $mode === 'login' ? 'BOM TER VOCÊ DE VOLTA' : 'VAMOS COMEÇAR?' }}</p>
        <h2>{{ $mode === 'login' ? 'Acesse sua conta' : 'Crie sua conta' }}</h2>
        <p class="card-description">
            {{ $mode === 'login' ? 'Entre para cuidar do seu cardápio.' : 'Leva só um minutinho para começar.' }}
        </p>
    </div>

    @if ($audience === 'visitor')
        <div class="auth-tabs" role="tablist" aria-label="Acesso à conta">
            <button
                class="auth-tab {{ $mode === 'login' ? 'is-active' : '' }}"
                type="button"
                role="tab"
                aria-selected="{{ $mode === 'login' ? 'true' : 'false' }}"
                wire:click="setMode('login')"
            >Entrar</button>
            <button
                class="auth-tab {{ $mode === 'register' ? 'is-active' : '' }}"
                type="button"
                role="tab"
                aria-selected="{{ $mode === 'register' ? 'true' : 'false' }}"
                wire:click="setMode('register')"
            >Criar conta</button>
        </div>
    @endif

    <form class="auth-form" wire:submit="{{ $mode === 'login' ? 'login' : 'register' }}">
        @if ($mode === 'register')
            <div class="form-field">
                <label for="name">Seu nome</label>
                <input
                    id="name"
                    type="text"
                    wire:model="name"
                    autocomplete="name"
                    placeholder="Como podemos te chamar?"
                    required
                >
                @error('name') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="form-field">
            <label for="email">E-mail</label>
            <input
                id="email"
                type="email"
                wire:model="email"
                autocomplete="email"
                placeholder="voce@exemplo.com"
                required
            >
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field">
            <label for="password">Senha</label>
            <input
                id="password"
                type="password"
                wire:model="password"
                autocomplete="{{ $mode === 'login' ? 'current-password' : 'new-password' }}"
                placeholder="{{ $mode === 'login' ? 'Digite sua senha' : 'Pelo menos 8 caracteres' }}"
                minlength="{{ $mode === 'register' ? '8' : '1' }}"
                required
            >
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        @if ($mode === 'register')
            <div class="form-field">
                <label for="password_confirmation">Confirme sua senha</label>
                <input
                    id="password_confirmation"
                    type="password"
                    wire:model="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Digite a senha novamente"
                    minlength="8"
                    required
                >
            </div>
        @endif

        <button class="primary-button" type="submit" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ $mode === 'login' ? 'Entrar na minha conta' : 'Criar minha conta' }}</span>
            <span wire:loading>Só um instante...</span>
            <span class="button-arrow" aria-hidden="true">→</span>
        </button>
    </form>

    @if ($audience === 'visitor')
        <p class="card-switch">
            {{ $mode === 'login' ? 'Ainda não tem uma conta?' : 'Já tem uma conta?' }}
            <button
                type="button"
                wire:click="setMode('{{ $mode === 'login' ? 'register' : 'login' }}')"
            >{{ $mode === 'login' ? 'Cadastre-se' : 'Entre por aqui' }}</button>
        </p>
    @endif
</div>
