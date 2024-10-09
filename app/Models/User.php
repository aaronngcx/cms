<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($roleName)
    {
        return $this->roles()->first()->name === $roleName;
    }

    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    public function isEditor()
    {
        return $this->hasRole('Editor');
    }

    public function isAuthor()
    {
        return $this->hasRole('Author');
    }

    public function canEdit()
    {
        $user = Auth::user();
        
        return $user && (
            $user->id == $this->created_by || 
            $user->hasRole('Admin') || 
            $user->hasRole('Editor') ||
            $user->hasRole('Author')
        );
    }
}
