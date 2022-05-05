<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function hasRole($role) {
        if ($this->role === 'administrator') {
            return true;
        }
        if ($this->role === 'editor' && ($role === 'editor' || $role === 'registered' || $role === 'guest')) {
            return true;
        }
        if ($this->role === 'registered' && ($role === 'registered' || $role === 'guest')) {
            return true;
        }
        if ($this->role === 'guest' && $role === 'guest') {
            return true;
        }

        return false;
    }

    public function getAccessLevel() {
        if ($this->role === 'administrator') return 4;
        if ($this->role === 'editor') return 3;
        if ($this->role === 'registered') return 2;
        if ($this->role === 'guest') return 1;

        return 0;
    }

    public function getAvatarAttribute($value) {
        if ($value) {
            if (strpos($value, 'http://') !== false || strpos($value, 'https://') !== false) {
                return $value;
            }
            return Storage::url($value);
        }

        return Storage::url('avatars/noavatar.png');
    }

    public function deleteAvatarFile() {
        $avatar = $this->getOriginal('avatar');
        if ($avatar && strpos($avatar, 'http://') === false && strpos($avatar, 'https://') === false) {
            Storage::disk('public')->delete($avatar);
        }
    }
}
