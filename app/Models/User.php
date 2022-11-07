<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'password',
        'birthdate',
        'role_id',
        'telegram_id'
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
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wishes()
    {
        return $this->belongsToMany(
            Product::class,
            'wish_list',
            'user_id',
            'product_id'
        );
    }

    /**
     * @param Product $product
     */
    public function addToWish(Product $product)
    {
        $this->wishes()->attach($product);
    }

    /**
     * @param Product $product
     */
    public function removeFromWish(Product $product)
    {
        $this->wishes()->detach($product);
    }

    public function isWishedProduct(Product $product)
    {
        return (bool)$this->wishes()->find($product->id);
    }

    /**
     * Mutators $user->is_admin
     *
     * @return Attribute
     */

    public function isAdmin(): Attribute
    {
        return new Attribute(
            get: fn () => $this->role->id === Role::admin()->first()->id
        );
    }

    public function fullName(): Attribute
    {
        return new Attribute(
            get: fn () => ucfirst($this->attributes['name']).' '.ucfirst($this->attributes['surname'])
        );
    }
}
