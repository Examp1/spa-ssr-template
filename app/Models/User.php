<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Owlwebdev\Ecom\Models\Order;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Owlwebdev\Ecom\Models\Wishlist;
use Owlwebdev\Ecom\Models\Discount;

/**
 * Class User
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
 * @property string $provider_id
 * @property string $provider_name
 * @property integer $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasFactory;
    use HasRoles;

    protected $table = 'users';

    protected $guard = 'api';

    const STATUS_NOT_ACTIVE = 0; // Не активний
    const STATUS_REGISTER   = 1; // Зареєстрований

    const NO_USER   = 1; // id of undestroyable user to skip login

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'surname', 'email', 'password', 'role',
        'phone', 'country', 'city', 'address', 'apartment', 'postcode', 'birthday',
        'status', 'email_verified_at', 'phone_verified_at', 'wishlist_share', 'discount_id', 'provider_id', 'provider_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'wishlist_share',
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

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('users.status', self::STATUS_REGISTER);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('users.status', self::STATUS_NOT_ACTIVE);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function mainAddress()
    {
        return $this->addresses()->where('main', true)->first();
    }

    //all
    public function carts()
    {
        return $this->hasMany(Order::class);
    }

    //public orders
    public function orders()
    {
        return $this->carts()->whereIn('order_status_id', Order::PUBLIC_STATUSES);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function discount()
    {
        return $this->hasOne(Discount::class, 'id', 'discount_id')->active()->withDefault(null);
    }
}
