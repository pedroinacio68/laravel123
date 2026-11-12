<?php

namespace Tests\Feature;

use App\Livewire\MenuManager;
use App\Models\DailyMenu;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Tests\TestCase;

class DailyMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_must_log_in_before_opening_the_daily_menu(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Área do visitante')
            ->assertDontSee('Sou visitante');

        $this->get(route('visitor.menu'))
            ->assertRedirect(route('visitor.login'));

        $this->get(route('visitor.login'))
            ->assertOk()
            ->assertSee('ÁREA DO VISITANTE')
            ->assertSee('Criar conta');
    }

    public function test_an_administrator_can_publish_food_for_visitors(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test(MenuManager::class)
            ->set('title', 'Almoço de hoje')
            ->set('description', 'Comida caseira feita na hora.')
            ->call('saveMenu')
            ->assertHasNoErrors();

        Livewire::test(MenuManager::class)
            ->set('foodName', 'Frango grelhado')
            ->set('foodDescription', 'Acompanha arroz e salada.')
            ->set('foodPrice', '18.50')
            ->call('addFood')
            ->assertHasNoErrors();

        Auth::logout();
        $this->assertGuest();

        $this->get(route('visitor.menu'))
            ->assertRedirect(route('visitor.login'));

        $visitor = User::factory()->create(['role' => 'visitor']);
        $this->actingAs($visitor)
            ->get(route('visitor.menu'))
            ->assertOk()
            ->assertSee('Almoço de hoje')
            ->assertSee('Frango grelhado')
            ->assertSee('Acompanha arroz e salada.')
            ->assertSee('R$ 18,50');

        $food = MenuItem::where('name', 'Frango grelhado')->firstOrFail();
        $this->actingAs($admin);

        Livewire::test(MenuManager::class)
            ->call('removeFood', $food->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('menu_items', ['id' => $food->id]);
    }

    public function test_administrators_share_one_menu_per_day(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test(MenuManager::class)
            ->set('title', 'Almoço')
            ->call('saveMenu');

        $this->actingAs($otherAdmin);

        Livewire::test(MenuManager::class)
            ->set('title', 'Almoço especial')
            ->call('saveMenu');

        $this->assertSame(1, DailyMenu::whereDate('menu_date', today())->count());
        $this->assertSame(
            'Almoço especial',
            DailyMenu::whereDate('menu_date', today())->value('title'),
        );
    }

    public function test_food_cannot_be_added_before_saving_the_daily_menu(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        Livewire::test(MenuManager::class)
            ->set('foodName', 'Feijoada')
            ->call('addFood')
            ->assertHasErrors(['foodName']);
    }

    public function test_a_visitor_cannot_open_the_administrator_dashboard(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'visitor']))
            ->get(route('dashboard'))
            ->assertForbidden();
    }
}
