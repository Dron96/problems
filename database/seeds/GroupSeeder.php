<?php

use App\Models\User;
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

        $groupNames = [
            1 => 'Отдел маркетинга',
            2 =>'Отдел разработки',
            3 => 'Отдел аналитики',
            4 => 'Техническая поддержка',
            5 => 'Администрация'
        ];

        for ($i = 1; $i <= 5; $i++){
            $created_at = $faker->dateTimeBetween('-3 months','-10 day');
            $name = $groupNames[$i];
            $user = factory(User::class)->create();
            $leader_id = $user->id;

            $list[] = [
                'leader_id' => $leader_id,
                'name' => $name,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ];

            DB::table('groups')->insert($list);
            $user->update(['group_id' => $i]);
        }
    }
}
