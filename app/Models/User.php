<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plain_password',
        'join_date',
        'end_date',
        'status',
        'commission',
        'rate',
        'max_limit',
        'manager_id',
        'open_time_am',
        'close_time_am',
        'open_time_pm',
        'close_time_pm',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }


    public function users()
    {
        return $this->hasMany(User::class, 'manager_id');
    }


    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

}
