<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class ProblemSeeder extends Seeder
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

        for ($i = 1; $i <= 9; $i++){
            $name = $faker->realText(rand(10, 55));
            $created_at = $faker->dateTimeBetween('-3 months','-10 day');

            $list[] = [
                'name' => $name,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ];
        }

        DB::table('problems')->insert($list);
    }
}
