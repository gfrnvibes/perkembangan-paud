<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Wirechat\Wirechat\Panel;
use App\Models\Master\Student;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wirechat\Wirechat\Contracts\WirechatUser;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Wirechat\Wirechat\Traits\InteractsWithWirechat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements WirechatUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, InteractsWithWirechat;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address'
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
        ];
    }

    // public function children()
    // {
    //     return $this->hasMany(Student::class, 'student_parent');
    // }

    public function children()
    {
        return $this->belongsToMany(Student::class, 'student_parent', 'user_id', 'student_id');
    }


        public function canAccessWirechatPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * Control whether this user is allowed to create 1-to-1 chats.
     */
    public function canCreateChats(): bool
    {
        return true;
    }

    /**
     * Control whether this user can create group conversations.
     */
    public function canCreateGroups(): bool
    {
        return true;
    }
}
