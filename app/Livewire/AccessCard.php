<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class AccessCard extends Component
{
    public string $mode = 'login';

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function setMode(string $mode): void
    {
        if (! in_array($mode, ['login', 'register'], true)) {
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

        if (! Auth::attempt($credentials)) {
            $this->addError('email', 'E-mail ou senha inválidos.');

            return;
        }

        session()->regenerate();

        $this->redirectRoute('dashboard');
    }

    public function register(): void
    {
        $this->email = mb_strtolower($this->email);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
        ]);

        $user = User::create($validated);

        Auth::login($user);
        session()->regenerate();

        $this->redirectRoute('dashboard');
    }

    public function render()
    {
        return view('livewire.access-card');
    }
}
