<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'images',
        'status',
        'users_id',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('user_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'task_id');
    }
    public function getLastImageAttribute()
    {
        $images = json_decode($this->attributes['images'], true);
        return last($images);
    }

}
