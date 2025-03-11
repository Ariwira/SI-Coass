<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory as Faker;

class UsersDokterSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $data = [
                'email' => $faker->unique()->safeEmail,
                'password' => password_hash('dokter@2025', PASSWORD_BCRYPT), // Password yang sama untuk semua
                'role' => 'Dokter',
                'remember_token' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->table('users')->insert($data);
        }
    }
}
