<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class SolutionSeeder extends Seeder
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
        $statuses = [null, 'В работе', 'Завершено'];

        for ($i = 1; $i <= 100; $i++){
            $name = $faker->realText(rand(10, 55));
            $created_at = $faker->dateTimeBetween('-3 months','-10 day');
            $inWork = rand(0, 1);
            if ($inWork === 1) {
                $status = $statuses[array_rand($statuses)];
            } else {
                $status = null;
            }
            $userId = rand(1, 15);
            $problemId = rand(1, 30);

            $list[] = [
                'name' => $name,
                'in_work' => $inWork,
                'status' => $status,
                'user_id' => $userId,
                'problem_id' => $problemId,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ];
        }

        DB::table('solutions')->insert($list);
    }
}
