<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class GroupSeeder extends Seeder
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

        for ($i = 1; $i <= 3; $i++){
            $created_at = $faker->dateTimeBetween('-3 months','-10 day');
            $name = $faker->company;
            $shortName = $faker->text(10);
            $leader_id = rand(1, 20);

            $list[] = [
                'leader_id' => $leader_id,
                'name' => $name,
                'short_name'=> $shortName,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ];
        }

        DB::table('groups')->insert($list);
    }
}
