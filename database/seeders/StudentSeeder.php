<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            Student::create([
                'nis' => $i,
                'name' => $faker->name('male'), // Menghasilkan nama acak dengan jenis kelamin laki-laki
                'gender' => 'Male',
                'birthday' => $faker->date(),
                'religion' => 'Islam',
                'contact' => $faker->phoneNumber, // Menghasilkan nomor telepon acak
                'profile' => $faker->imageUrl(), // Menghasilkan URL gambar acak
                'status' => 'Accept',
            ]);
        }
    }
}
