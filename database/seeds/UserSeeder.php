<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     * @return void
     */
    public function run(Faker $faker)
    {
        $list = [];

        for ($i = 1; $i <= 15; $i++) {
            $name = $faker->firstName;
            $surname = $faker->lastName;
            $father_name = $faker->middleName;
            $email = $faker->unique()->safeEmail;
            $password = bcrypt('password');
            if (rand(0, 1) === 1) {
                $group = rand(1, 3);
            } else {
                $group = NULL;
            }

            $list[] = [
                'name' => $name,
                'surname' => $surname,
                'father_name' => $father_name,
                'email' => $email,
                'password' => $password,
                'group_id' => $group
            ];
        }

        DB::table('users')->insert($list);
    }
}
