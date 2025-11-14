<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;
    protected $table='resources';
    protected $fillable=[
        'name','description','marca','status_resource_id','category_id'
    ];
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
     public function status(){
        return $this->belongsTo(StatusResource::class,'status_resource_id');
    }
     public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_resources', 'resource_id', 'reservation_id');
    }
}
