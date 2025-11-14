<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationResource extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationResourceFactory> */
    use HasFactory;
    protected $table='reservation_resources';
    protected $fillable=[
        'resource_id','reservation_id'
    ];
    public function resource(){
        return $this->belongsTo(Resource::class,'resource_id');
    }
    public function reservation(){
        return $this->belongsTo(Reservation::class,'reservation_id');
    }
}
