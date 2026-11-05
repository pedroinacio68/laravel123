<?php

namespace App\Livewire;

use App\Models\DailyMenu;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MenuManager extends Component
{
    public string $menuDate = '';

    public string $title = '';

    public string $description = '';

    public string $foodName = '';

    public string $foodDescription = '';

    public string $foodPrice = '';

    public string $notice = '';

    public function mount(): void
    {
        $this->menuDate = today()->toDateString();
        $this->loadMenu();
    }

    public function updatedMenuDate(): void
    {
        $this->validateOnly('menuDate', [
            'menuDate' => ['required', 'date_format:Y-m-d'],
        ]);

        $this->resetValidation();
        $this->notice = '';
        $this->loadMenu();
    }

    public function saveMenu(): void
    {
        abort_unless(Auth::check(), 403);

        $validated = $this->validate([
            'menuDate' => ['required', 'date_format:Y-m-d'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $menu = $this->selectedMenu() ?? new DailyMenu(['menu_date' => $validated['menuDate']]);
        $menu->fill([
            'title' => $validated['title'],
            'description' => $validated['description'] ?: null,
        ])->save();

        $this->notice = 'Cardápio salvo com sucesso.';
    }

    public function addFood(): void
    {
        abort_unless(Auth::check(), 403);

        $validated = $this->validate([
            'menuDate' => ['required', 'date_format:Y-m-d'],
            'foodName' => ['required', 'string', 'max:120'],
            'foodDescription' => ['nullable', 'string', 'max:1000'],
            'foodPrice' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
        ]);

        $menu = $this->selectedMenu();

        if (! $menu) {
            $this->addError('foodName', 'Salve o cardápio desta data antes de adicionar alimentos.');

            return;
        }

        $menu->items()->create([
            'name' => $validated['foodName'],
            'description' => $validated['foodDescription'] ?: null,
            'price' => $validated['foodPrice'] !== '' ? $validated['foodPrice'] : null,
        ]);

        $this->reset(['foodName', 'foodDescription', 'foodPrice']);
        $this->notice = 'Alimento adicionado ao cardápio.';
    }

    public function removeFood(int $foodId): void
    {
        abort_unless(Auth::check(), 403);

        $menu = $this->selectedMenu();

        if (! $menu) {
            return;
        }

        $menu->items()->whereKey($foodId)->firstOrFail()->delete();
        $this->notice = 'Alimento removido do cardápio.';
    }

    private function loadMenu(): void
    {
        $this->validate([
            'menuDate' => ['required', 'date_format:Y-m-d'],
        ]);

        $menu = $this->selectedMenu();
        $this->title = $menu?->title ?? '';
        $this->description = $menu?->description ?? '';
    }

    private function selectedMenu(): ?DailyMenu
    {
        return DailyMenu::query()
            ->whereDate('menu_date', $this->menuDate)
            ->with('items')
            ->first();
    }

    public function render()
    {
        return view('livewire.menu-manager', [
            'menu' => $this->selectedMenu(),
        ]);
    }
}
