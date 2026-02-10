<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'work_start_time',
        'work_end_time',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    /**
     * Check if the user is a master.
     */
    public function isMaster(): bool
    {
        return $this->role === 'master';
    }

    /**
     * Check if the user is an owner.
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if the user is master or owner.
     */
    public function isMasterOrOwner(): bool
    {
        return in_array($this->role, ['master', 'owner']);
    }

    /**
     * Check if the user is a marketer.
     */
    public function isMarketer(): bool
    {
        return $this->role === 'marketer';
    }

    /**
     * Check if the user is a content creator.
     */
    public function isContentCreator(): bool
    {
        return $this->role === 'content_creator';
    }

    /**
     * Check if user has permission to a specific module.
     */
    public function hasPermission(string $module): bool
    {
        // Master always has all permissions
        if ($this->isMaster()) {
            return true;
        }

        if (!$this->permissions) {
            return false;
        }

        return in_array($module, $this->permissions);
    }
}
