<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory as Faker;

class MahasiswaCoassSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($userId = 2; $userId <= 21; $userId++) {
            $data = [
                'user_id' => $userId, // Mengaitkan dengan user biasa
                'name' => $faker->name,
                'date_of_birth' => $faker->date(),
                'place_of_birth' => $faker->city,
                'nim' => $faker->unique()->numerify('##########'),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'religion' => $faker->randomElement(['Islam', 'Hindu', 'Protestan', 'Katolik', 'Buddha', 'Konghucu']),
                'blood_group' => $faker->randomElement(['A', 'B', 'AB', 'O', 'Unknown']),
                'phone' => $faker->phoneNumber,
                'mobile_no' => $faker->phoneNumber,
                'address' => $faker->address,
                'university' => $faker->company,
                'year' => $faker->year,
                'photo' => 'default-avatar.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];


            $this->db->table('mahasiswa_coass')->insert($data);
        }
    }
}
