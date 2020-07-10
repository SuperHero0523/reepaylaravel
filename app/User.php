<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Silvanite\Brandenburg\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasMediaTrait, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'description',
        'email',
        'password',
        'password_autogenerated',
        'city',
        'postal_code',
        'country',
        'address'
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
    ];

    public function registerMediaCollections()
    {
        $this->addMediaCollection('profile')->singleFile();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('small')
            ->width(32)
            ->height(32);

        $this->addMediaConversion('dashboard')
            ->width(50)
            ->height(50);

        $this->addMediaConversion('full')
            ->width(512)
            ->height(512);
    }

    public function getCity()
    {
        return $this->hasOne('App\Cities', 'id', 'city');
    }

    public function payment()
    {
        return $this->hasOne('App\UserPayment');
    }

    public function paymentMethods()
    {
        return $this->hasMany('App\UserPaymentMethods');
    }

    public function hasActiveSubscription()
    {
        if ($this->roles()->where('slug', 'subscriber')->count()) {
            return true;
        }

        return false;
    }
}