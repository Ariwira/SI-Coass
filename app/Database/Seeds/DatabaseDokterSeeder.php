<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseDokterSeeder extends Seeder
{
    public function run()
    {
        // Call the UsersSeeder
        $this->call(UsersDokterSeeder::class);

        // Call the DokterSeeder
        $this->call(DokterSeeder::class);
    }
}
