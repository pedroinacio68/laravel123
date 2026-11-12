<?php

namespace Tests\Feature;

use App\Livewire\AccessCard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_the_access_card(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Um cardápio que abre o')
            ->assertSee('Área do visitante')
            ->assertDontSee('Sou visitante')
            ->assertDontSee('Criar conta')
            ->assertSeeLivewire(AccessCard::class);
    }

    public function test_a_visitor_can_register_then_log_in_to_view_the_menu(): void
    {
        Livewire::test(AccessCard::class, ['audience' => 'visitor'])
            ->call('setMode', 'register')
            ->set('name', 'Ana Souza')
            ->set('email', 'ANA@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertRedirect(route('visitor.login'));

        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'name' => 'Ana Souza',
            'email' => 'ana@example.com',
            'role' => 'visitor',
        ]);

        Livewire::test(AccessCard::class, ['audience' => 'visitor'])
            ->set('email', 'ana@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect(route('visitor.menu'));

        $this->assertAuthenticated();
    }

    public function test_an_administrator_can_log_in_but_cannot_register(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        Livewire::test(AccessCard::class)
            ->set('email', $admin->email)
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($admin);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('PAINEL ADMINISTRATIVO');

        $this->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();

        Livewire::test(AccessCard::class)
            ->call('setMode', 'register')
            ->assertSet('mode', 'login');
    }

    public function test_a_guest_cannot_access_the_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_an_administrator_cannot_log_in_through_the_visitor_form(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => 'password123',
        ]);

        Livewire::test(AccessCard::class, ['audience' => 'visitor'])
            ->set('email', $admin->email)
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }
}
