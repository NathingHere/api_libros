<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = [
        'title', 'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'libro_users');
    }
}
