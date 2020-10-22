<?php

use App\Models\Group;
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
            2 => 'Отдел разработки',
            3 => 'Отдел аналитики',
            4 => 'Техническая поддержка',
            5 => 'Администрация',
            6 => 'Отдел тестирования',
            7 => 'Отдел статегического развития',
            8 => 'Отдел кадров',
            9 => 'Хозяйственный отдел',
            10 => 'Бухгалтерия',
        ];

        for ($i = 1; $i <= 10; $i++){
            $created_at = $faker->dateTimeBetween('-3 months','-10 day');
            $name = $groupNames[$i];

            if (rand(0, 1) === 0) {
                $user = User::create([
                    'name' => $faker->firstName('male'),
                    'surname' => $faker->lastName('male'),
                    'father_name' => $faker->middleName('male'),
                    'email' => 'test' . $i . '@pss.ru',
                    'password' => bcrypt('12345678')
                ]);
            } else {
                $user = User::create([
                    'name' => $faker->firstName('female'),
                    'surname' => $faker->lastName('female'),
                    'father_name' => $faker->middleName('female'),
                    'email' => 'test' . $i . '@pss.ru',
                    'password' => bcrypt('12345678')
                ]);
            }

            $group = [
                'leader_id' => $user->id,
                'name' => $name,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ];

            Group::create($group);
            $user->update(['group_id' => $i]);
        }
    }
}
