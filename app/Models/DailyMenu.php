<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyMenu extends Model
{
    protected $fillable = [
        'menu_date',
        'title',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'menu_date' => 'date',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
