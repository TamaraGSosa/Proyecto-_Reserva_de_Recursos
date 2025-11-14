<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LDAP\Result;

class StatusReservation extends Model
{
    protected $table='status_reservations';
    protected $fillable=['name'];

    public function reservation(){
        return $this->hasMany(Reservation::class,'status_reservation_id');
    }
}
