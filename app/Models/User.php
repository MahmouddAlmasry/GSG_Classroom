<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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

    //Mutatore
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower('$value');
    }

    public function classrooms()
    {
        return $this->belongsToMany(ClassroomModel::class, 'classroom_user', 'user_id', 'classroom_id', 'id', 'id')
            ->withPivot(['role']);
    }

    public function createdClassrooms()
    {
        return $this->hasMany(ClassroomModel::class, 'user_id');
    }

    public function classworks()
    {
        return $this->belongsToMany(Classwork::class)
            ->using(ClassworkUser::class)
            ->withPivot(['grade', 'status', 'submitted_at', 'created_at']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function profile()
    {
       return $this->hasOne(Profile::class, 'user_id', 'id')
            ->withDefault();
    }

    public function preferredLocale()
    {
        return $this->profile->locale;
    }

    public function routeNotificationForVonage($notification = null)
    {
        return '972592808419';
    }

    public function routeNotificationForHadara($notification = null)
    {
        return '972592808419';
    }

    public function subscrioitions()
    {
        return $this->hasMany(Subscription::class);
    }
}
