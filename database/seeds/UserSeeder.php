<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Faker\Provider\ru_RU\Person as Person;

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
        $faker->addProvider(new Person($faker));

        $list = [];

        for ($i = 1; $i <= 15; $i++) {
            if (rand(0, 1) === 0) {
                $list[] = [
                    'name' => $faker->firstName('male'),
                    'surname' => $faker->lastName('male'),
                    'father_name' => $faker->middleName('male'),
                    'email' => $faker->unique()->safeEmail,
                    'password' => bcrypt('password'),
                    'group_id' => rand(1, 10)
                ];
            } else {
                $list[] = [
                    'name' => $faker->firstName('female'),
                    'surname' => $faker->lastName('female'),
                    'father_name' => $faker->middleName('female'),
                    'email' => $faker->unique()->safeEmail,
                    'password' => bcrypt('password'),
                    'group_id' => rand(1, 10)
                ];
            }
        }

        DB::table('users')->insert($list);
    }
}
