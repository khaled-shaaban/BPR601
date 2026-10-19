<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\{Disableable, Searchable};
use App\Enums\AppRoles;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'phone', 'password', 'authority_id', 'notify_on_received', 'notify_on_status_change', 'notify_on_closed'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use Disableable, Searchable, HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * Guard used by Spatie roles/permissions for this model (matches the seeded guard).
     */
    protected $guard_name = 'api';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'authority_id' => 'integer',
            'notify_on_received' => 'boolean',
            'notify_on_status_change' => 'boolean',
            'notify_on_closed' => 'boolean',
        ];
    }

    /**
     * Determine if the user has an administrative role.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole([AppRoles::ADMIN->value, AppRoles::SUPER_ADMIN->value]);
    }

    /**
     * Determine if the user is an authority representative.
     */
    public function isRepresentative(): bool
    {
        return $this->hasRole(AppRoles::REPRESENTATIVE->value);
    }

    /**
     * Staff = administrators or authority representatives (i.e. not citizens).
     */
    public function isStaff(): bool
    {
        return $this->isAdmin() || $this->isRepresentative();
    }

    public function authority()
    {
        return $this->belongsTo(Authority::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
