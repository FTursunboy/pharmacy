<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const STATUS_SUCCESS = 'success';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'code',
        'shop_code',
        'notify_offers',
        'status',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function shops()
    {
        return $this->belongsTo(Shop::class, 'code', 'shop_code');
    }


    public function favourites() {
        return $this->belongsToMany(BookMarkedProducts::class, 'bookmarked_products', 'user_id', 'product_code');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            $user->phone = preg_replace('/[^0-9]/', '', $user->phone); // удаляем все символы, кроме цифр
            $user->phone = '+7' . ltrim($user->phone, '7'); // добавляем страновый код, если его нет
        });
    }

}
