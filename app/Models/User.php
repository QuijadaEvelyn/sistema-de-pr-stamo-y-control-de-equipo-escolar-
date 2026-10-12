<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'user_id');
    }

    public function handledLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'handled_by');
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'user_id');
    }

    public function isSolicitante(): bool
    {
        return $this->role === 'solicitante';
    }

    public function isEncargado(): bool
    {
        return $this->role === 'encargado';
    }
}
