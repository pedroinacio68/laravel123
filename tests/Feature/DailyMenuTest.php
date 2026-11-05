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

    public function test_a_visitor_can_open_the_daily_menu_without_logging_in(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Sou visitante')
            ->assertSee(route('public-menu'));

        $this->get(route('public-menu'))
            ->assertOk()
            ->assertSee('CHAVE PIX CPF · DEMONSTRAÇÃO')
            ->assertSee('000.000.000-00')
            ->assertSee('Este CPF é fictício e não recebe pagamentos.')
            ->assertSee('O cardápio de hoje ainda não foi publicado');
    }

    public function test_an_administrator_can_publish_food_for_visitors(): void
    {
        $admin = User::factory()->create();
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

        $this->get(route('public-menu'))
            ->assertOk()
            ->assertSee('Almoço de hoje')
            ->assertSee('Frango grelhado')
            ->assertSee('Acompanha arroz e salada.')
            ->assertSee('R$ 18,50')
            ->assertSee('000.000.000-00');

        $food = MenuItem::where('name', 'Frango grelhado')->firstOrFail();
        $this->actingAs($admin);

        Livewire::test(MenuManager::class)
            ->call('removeFood', $food->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('menu_items', ['id' => $food->id]);
    }

    public function test_administrators_share_one_menu_per_day(): void
    {
        $admin = User::factory()->create();
        $otherAdmin = User::factory()->create();
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
        $this->actingAs(User::factory()->create());

        Livewire::test(MenuManager::class)
            ->set('foodName', 'Feijoada')
            ->call('addFood')
            ->assertHasErrors(['foodName']);
    }
}
