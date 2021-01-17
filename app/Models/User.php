<?php

namespace App\Models;

use App\Service\ImageUpload;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable {
        notify as parentNotify;
    }

    use HasRoles;

    use Traits\ActiveUser;

    use Traits\LastLoginTime;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'avatar', 'intro', 'notify_count',
        'last_login_time', 'phone',
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

    public function notify($instance)
    {
        if($this->id === Auth::id()) {
            return;
        }
        $this->increment('notify_count');
        $this->parentNotify($instance);
    }

    public function getAvatarAttribute($value)
    {
        if(! $value) {
            $value = asset('image/default.png');
        }

        return (new ImageUpload)->getFullUrl($value);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function markAsRead()
    {
        $this->unreadNotifications->markAsRead();
        $this->update(['notify_count' => 0]);
    }

    public function getLastLoginTimeAttribute($value)
    {
        if($temp = $this->getLastLoginTime()) {
            return Carbon::make($temp);
        } else if($value) {
            return $value;
        } else {
            return $this->created_at;
        }
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
