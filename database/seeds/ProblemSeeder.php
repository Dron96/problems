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

        for ($i = 1; $i <= 30; $i++){
            $name = $faker->realText(rand(10, 55));
            $createdAt = $faker->dateTimeBetween('-3 months','-10 day');
            $creatorId = rand(1, 19);

            $list[] = [
                'name' => $name,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'creator_id' => $creatorId,
            ];
        }

        DB::table('problems')->insert($list);
    }
}
