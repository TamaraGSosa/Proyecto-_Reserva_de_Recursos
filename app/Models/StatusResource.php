<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusResource extends Model
{
    use HasFactory;
    protected $table='status_resources';

    protected $fillable=[
        'name'
    ];

    public function resources(){
        return $this->hasMany(Resource::class,'status_resource_id');
    }
     public function state()
    {
        return $this->belongsTo(StatusResource::class, 'status_resource_id');
    }
}
