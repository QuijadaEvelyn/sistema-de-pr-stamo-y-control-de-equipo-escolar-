<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';

    protected $fillable = [
        'user_id',
        'handled_by',
        'start_date',
        'due_date',
        'status',
        'notes',
        'delivered_at',
        'returned_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'delivered_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LoanItem::class, 'loan_id');
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'loan_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pendiente';
    }

    public function isApproved(): bool
    {
        return $this->status === 'aprobado';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'entregado';
    }

    public function isReturned(): bool
    {
        return $this->status === 'devuelto';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rechazado';
    }
}
