<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseMahasiswaSeeder extends Seeder
{
    public function run()
    {
        // Call the UsersSeeder
        $this->call(UsersMahasiswaSeeder::class);

        // Call the MahasiswaCoassSeeder
        $this->call(MahasiswaCoassSeeder::class);
    }
}
