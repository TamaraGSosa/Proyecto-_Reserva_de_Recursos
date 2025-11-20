<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;
    protected $table ='reservations';
    protected $fillable=[
        'profile_id','status_reservation_id','start_time','end_time', 'create_by_user_id',
    ];
    protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    ];

    public function profile(){
        return $this->belongsTo(Profile::class,'profile_id');

    }
      public function status(){
        return $this->belongsTo(StatusReservation::class,'status_reservation_id');
        
    }
       public function creator()
    {
        return $this->belongsTo(User::class, 'create_by_user_id');
    }
        public function resources()
    {
        return $this->belongsToMany(
            Resource::class,
            'reservation_resources', // tabla pivot
            'reservation_id',         // FK de Reservation en la tabla pivot
            'resource_id'             // FK de Resource en la tabla pivot
        )->withTimestamps();         // si querés guardar created_at y updated_at en la pivot
    }

}
