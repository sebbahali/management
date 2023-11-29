<?php

namespace App\Models;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class User extends Authenticatable  implements HasMedia , MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatars',
        'department_id',
        'start_date',
        'birthday_date',
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
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb-50')
              ->width(50)
              ->height(50)
              ;

              $this->addMediaConversion('thumb-100')
              ->width(50)
              ->height(50)
              ;
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
   
    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }
}


