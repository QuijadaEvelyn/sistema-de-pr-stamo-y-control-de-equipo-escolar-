<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'Incidents';

    protected $fillable = [
        'User_id',
        'Equipment_id',
        'Loan_id',
        'title',
        'description',
        'status',
        'resolution',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'abierta';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'en_proceso';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resuelta';
    }
}
