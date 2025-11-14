<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /** @use HasFactory<\Database\Factories\PersonFactory> */
    use HasFactory;

    protected $fillable = ['DNI', 'first_name', 'last_name', 'email'];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
