<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Admin
 * @package App
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $phone_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $phone
 * @property integer $status
 * @property integer $crm_access
 * @property integer $crm_sync
 * @property string $crm_access_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class Admin extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use HasRoles;

    protected $table = 'admins';

    const STATUS_NOT_ACTIVE = 0; // Не активний
    const STATUS_REGISTER   = 1; // Зареєстрований

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'crm_access',
        'crm_sync',
        'crm_access_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NOT_ACTIVE => 'Не активний',
            self::STATUS_REGISTER   => 'Зареєстрований'
        ];
    }

    /**
     * @return array
     */
    public static function getStatusColors(): array
    {
        return [
            self::STATUS_NOT_ACTIVE => ['#ff3838', '#fdfdfd'],
            self::STATUS_REGISTER   => ['#fce83a', '#4f4706'],
        ];
    }
}
