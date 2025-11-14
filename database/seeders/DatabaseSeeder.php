<?php

namespace Database\Seeders;

use App\Models\Resource;

use App\Models\Person;
use App\Models\Profile;
use App\Models\StatusReservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RolSeeder::class);
        $this->call(
            UserSeeder::class
        );
        $this->call(CategorySeeder::class);
        $this->call(StatusResourceSeeder::class);
       $this->call(StatusReservationSeeder::class); 
        


      
        
        Resource::factory(20)->create();
        Person::factory(10)->create(); // crea 10 personas
        Profile::factory(5)->create(); // crea 5 perfiles con usuarios y personas
        // Reservation::factory(10)->create(); // Deshabilitado: usuarios empiezan sin reservas
        
    }
}
