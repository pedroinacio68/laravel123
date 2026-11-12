<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AccessCard extends Component
{
    #[Locked]
    public string $audience = 'admin';

    public string $mode = 'login';

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $audience = 'admin'): void
    {
        abort_unless(in_array($audience, ['admin', 'visitor'], true), 404);

        $this->audience = $audience;
    }

    public function setMode(string $mode): void
    {
        $availableModes = $this->audience === 'visitor' ? ['login', 'register'] : ['login'];

        if (! in_array($mode, $availableModes, true)) {
            return;
        }

        $this->mode = $mode;
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
    }

    public function login(): void
    {
        $this->email = mb_strtolower($this->email);

        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials) || ! $this->authenticatedUserMatchesAudience()) {
            Auth::logout();
            $this->addError('email', 'E-mail ou senha inválidos.');

            return;
        }

        session()->regenerate();

        $this->redirectRoute($this->audience === 'admin' ? 'dashboard' : 'visitor.menu');
    }

    public function register(): void
    {
        abort_unless($this->audience === 'visitor', 403);

        $this->email = mb_strtolower($this->email);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
        ]);

        User::create([
            ...$validated,
            'role' => 'visitor',
        ]);

        session()->flash('status', 'Cadastro realizado. Entre com seu e-mail e senha para ver o cardápio.');
        $this->redirectRoute('visitor.login');
    }

    private function authenticatedUserMatchesAudience(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $this->audience === 'admin' ? $user->isAdmin() : $user->isVisitor();
    }

    public function render()
    {
        return view('livewire.access-card');
    }
}
