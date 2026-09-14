<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const REQUIRED_PROFILE_FIELDS = [
        'name',
        'email',
        'phone',
        'gender',
        'birth_date',
        'address',
        'city',
        'education',
        'school',
        'skills',
        'bio',
    ];

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
        'phone',
        'gender',
        'birth_date',
        'address',
        'city',
        'education',
        'school',
        'skills',
        'experience',
        'bio',
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
            'birth_date' => 'date',
        ];
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function isProfileComplete(): bool
    {
        foreach (self::REQUIRED_PROFILE_FIELDS as $field) {
            $value = $this->{$field};
            if ($value === null || (is_string($value) && trim($value) === '')) {
                return false;
            }
        }
        return true;
    }

    public function profileCompletionPercentage(): int
    {
        $total = count(self::REQUIRED_PROFILE_FIELDS);
        $filled = 0;
        foreach (self::REQUIRED_PROFILE_FIELDS as $field) {
            $value = $this->{$field};
            if ($value !== null && (!is_string($value) || trim($value) !== '')) {
                $filled++;
            }
        }
        return (int) round(($filled / $total) * 100);
    }
}
