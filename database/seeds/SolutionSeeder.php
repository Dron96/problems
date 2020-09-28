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
        $statuses = [null, 'В процессе', 'Выполнено'];

        for ($i = 1; $i <= 30; $i++) {
            $name = $faker->realText(rand(10, 55));
            $created_at = $faker->dateTimeBetween('-3 months', '-10 day');
            $status = $statuses[array_rand($statuses)];
            if ($status === 'В процессе' or $status === 'Выполнено') {
                $userId = rand(1, 18);
            } else {
                $userId = null;
            }
            $problemId = $i;

            $list[] = [
                'name' => $name,
                'status' => $status,
                'executor_id' => $userId,
                'problem_id' => $problemId,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ];
        }

        DB::table('solutions')->insert($list);
    }
}
