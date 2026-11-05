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
            ->assertSeeLivewire(AccessCard::class);
    }

    public function test_a_user_can_create_an_account_and_access_the_dashboard(): void
    {
        Livewire::test(AccessCard::class)
            ->set('mode', 'register')
            ->set('name', 'Ana Souza')
            ->set('email', 'ANA@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Ana Souza',
            'email' => 'ana@example.com',
        ]);
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Monte o cardápio do dia')
            ->assertSee('Alimentos do cardápio');
    }

    public function test_a_user_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create([
            'email' => 'ana@example.com',
            'password' => 'password123',
        ]);

        Livewire::test(AccessCard::class)
            ->set('email', $user->email)
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_a_guest_cannot_access_the_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }
}
