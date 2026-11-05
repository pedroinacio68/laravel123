<section class="menu-manager" aria-labelledby="menu-manager-title">
    <div class="menu-manager-heading">
        <div>
            <p class="card-kicker">GERENCIAR CARDÁPIO</p>
            <h2 id="menu-manager-title">Monte o cardápio do dia</h2>
            <p>As alterações ficam disponíveis para todos os visitantes.</p>
        </div>
        <a class="menu-preview-link" href="{{ route('public-menu') }}" target="_blank" rel="noopener">Abrir como visitante ↗</a>
    </div>

    @if ($notice)
        <p class="menu-notice" role="status">{{ $notice }}</p>
    @endif

    <form class="menu-form" wire:submit="saveMenu">
        <div class="menu-form-grid">
            <div class="form-field">
                <label for="menu-date">Data do cardápio</label>
                <input id="menu-date" type="date" wire:model.live="menuDate" required>
                @error('menuDate') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-field">
                <label for="menu-title">Nome do cardápio</label>
                <input id="menu-title" type="text" wire:model="title" maxlength="120" placeholder="Ex.: Almoço de hoje" required>
                @error('title') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-field menu-description-field">
                <label for="menu-description">Apresentação <span>(opcional)</span></label>
                <textarea id="menu-description" wire:model="description" maxlength="1000" rows="2" placeholder="Uma mensagem para quem vai conferir o cardápio"></textarea>
                @error('description') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>
        <button class="primary-button menu-save-button" type="submit" wire:loading.attr="disabled" wire:target="saveMenu">
            <span wire:loading.remove wire:target="saveMenu">Salvar cardápio</span>
            <span wire:loading wire:target="saveMenu">Salvando...</span>
        </button>
    </form>

    <div class="food-manager">
        <div class="food-manager-heading">
            <div>
                <h3>Alimentos do cardápio</h3>
                <p>{{ $menu ? 'Adicione pratos, bebidas e acompanhamentos.' : 'Salve o cardápio desta data para começar a adicionar alimentos.' }}</p>
            </div>
            @if ($menu)
                <span class="food-count">{{ $menu->items->count() }} {{ $menu->items->count() === 1 ? 'item' : 'itens' }}</span>
            @endif
        </div>

        @if ($menu)
            <form class="food-form" wire:submit="addFood">
                <div class="food-form-grid">
                    <div class="form-field">
                        <label for="food-name">Nome do alimento</label>
                        <input id="food-name" type="text" wire:model="foodName" maxlength="120" placeholder="Ex.: Prato feito" required>
                        @error('foodName') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-field">
                        <label for="food-price">Preço <span>(opcional)</span></label>
                        <input id="food-price" type="number" wire:model="foodPrice" min="0" max="999999.99" step="0.01" placeholder="0,00">
                        @error('foodPrice') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-field food-description-field">
                        <label for="food-description">Descrição <span>(opcional)</span></label>
                        <input id="food-description" type="text" wire:model="foodDescription" maxlength="1000" placeholder="Ingredientes ou detalhes do prato">
                        @error('foodDescription') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button class="secondary-button" type="submit" wire:loading.attr="disabled" wire:target="addFood">
                    <span wire:loading.remove wire:target="addFood">+ Adicionar alimento</span>
                    <span wire:loading wire:target="addFood">Adicionando...</span>
                </button>
            </form>

            @if ($menu->items->isNotEmpty())
                <ul class="food-list">
                    @foreach ($menu->items as $item)
                        <li class="food-row" wire:key="food-{{ $item->id }}">
                            <span class="food-row-icon" aria-hidden="true">✳</span>
                            <span class="food-row-copy">
                                <strong>{{ $item->name }}</strong>
                                @if ($item->description)
                                    <small>{{ $item->description }}</small>
                                @endif
                            </span>
                            <span class="food-row-price">{{ $item->price !== null ? 'R$ '.number_format((float) $item->price, 2, ',', '.') : 'A combinar' }}</span>
                            <button class="remove-food-button" type="button" wire:click="removeFood({{ $item->id }})" wire:confirm="Remover este alimento do cardápio?" aria-label="Remover {{ $item->name }}">Remover</button>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="food-empty-state">Nenhum alimento adicionado para esta data.</p>
            @endif
        @endif
    </div>
</section>
