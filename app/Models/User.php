<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasAvatar;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * This array specifies which attributes can be assigned in bulk (mass assignment).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',   // The name of the user
        'user',   // The username
        'password', // The user's password
        'role',    // The role of the user (Admin, Editor, or Consultant)
        'avatar',  // The avatar ID associated with the user
    ];

    /**
     * The attributes that should be hidden for serialization.
     * This array specifies which attributes should be hidden when serializing the model.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', // The password should not be exposed
        'remember_token', // The remember_token should not be exposed
    ];

    /**
     * Get the attributes that should be cast.
     * This method returns an array defining the casting of attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Cast password to hashed format
            'role' => 'string',      // Cast role to string
            'avatar' => 'integer',   // Cast avatar to integer
        ];
    }

    /**
     * Get the avatar URL for Filament.
     * This method retrieves the avatar URL associated with the user.
     * If the user does not have an avatar, a default avatar is used.
     *
     * @return string|null
     */
    public function getFilamentAvatarUrl(): ?string
    {
        $avatarId = $this->avatar ?? 1; // Default to avatar ID 1 if not set
        return asset("storage/avatars/{$avatarId}.png"); // Return the URL for the avatar image
    }

    /**
     * Get the name for Filament.
     * This method retrieves the name of the user to be used in Filament (admin panel).
     *
     * @return string
     */
    public function getFilamentName(): string
    {
        return "{$this->name}"; // Return the user's name
    }

    /**
     * Check if the user can access the Filament panel.
     * This method checks if the user has access to the Filament admin panel.
     * Only users with the role of 'Administrador', 'Editor', or 'Consultor' can access the panel.
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'Administrador' 
            || $this->role === 'Editor'
            || $this->role === 'Consultor';
    }
}