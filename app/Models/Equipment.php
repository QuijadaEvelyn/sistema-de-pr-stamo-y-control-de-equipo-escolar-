<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'description',
        'total_quantity',
        'available_quantity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_quantity' => 'integer',
            'available_quantity' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function loanItems(): HasMany
    {
        return $this->hasMany(LoanItem::class, 'equipment_id');
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'equipment_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'activo'
            && $this->available_quantity > 0;
    }
}
