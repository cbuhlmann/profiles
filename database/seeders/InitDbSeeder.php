<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class InitDbSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Bienvenue dans le seeder d\'initialisation de la base de données !');

        $activeProfiles = $this->command->ask('Combien voulez-vous créer de profils actifs ?');
        $inactiveProfiles = $this->command->ask('Combien voulez-vous créer de profils inactifs ?');
        $waitingProfiles = $this->command->ask('Combien voulez-vous créer de profils en attente ?');

        Profile::factory($activeProfiles)->create([
            'status' => Profile::STATUS_ACTIVE,
        ]);

        Profile::factory($inactiveProfiles)->create([
            'status' => Profile::STATUS_INACTIVE,
        ]);

        Profile::factory($waitingProfiles)->create([
            'status' => Profile::STATUS_WAITING,
        ]);
    }
}
