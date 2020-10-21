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
        $problems = [
            1 => 'Сломался кондиционер',
            2 => 'Новые клавиатуры слишком шумные',
            3 => 'Закрылась служба доставки обедов',
            4 => 'Плохая аналитика приводит к оттоку клиентов',
            5 => 'Системный администратор опаздывает на работу четвертый раз',
            6 => 'Не работает кулер в конференц-зале',
            7 => 'Нужен третий монитор',
            8 => 'Обновить скрипты для менеджеров',
            9 => 'Нужны продающие тексты в соц.сети',
            10 => 'На месяц отключат водоснабжение здания.',
            11 => 'Новый регламент работы в условиях карантина.',
            12 => 'Купить лицензию на ПО',
            13 => 'Не поступила оплата по проекту № 38',
            14 => 'Разработка не вписывается в бюджет',
            15 => 'Нужен новый технический писатель.'
        ];

        $solutions = [
            1 => 'Купить новый',
            2 => 'Поменять на менее шумные',
            3 => 'Перейти на другую службу',
            4 => 'Проанализировать работу отдела аналитики за последний квартал.',
            5 => 'Переговорить с нарушающим трудовую дисциплину сотрудником',
            6 => 'Вызвать мастера для починки',
            7 => 'Поставить сотруднику третий монитор',
            8 => 'Подготовить новые скрипты для менеджеров',
            9 => 'Подготовить продающие тексты',
            10 => 'Организовать дополнительную доставку воды.',
            11 => 'Купить термометры и назначить дежурных.',
            12 => 'Пока что нет свободного бюджета',
            13 => 'Направить юристам',
            14 => '',
            15 => 'Завербовать нового сотрудника'
        ];

        $tasks = [
            1 => ['Подобрать подходящий кондиционер', 'Заказать кондиционер у поставщика'],
            2 => ['Подобрать бесшумную клавиатуру', 'Заказать комплект выбранных клавиатур'],
            3 => ['Проанализировать предложения',
                'Выбрать новую службу доставки',
                'Связаться с новой службой доставки',
                'Заключить договор обслуживания.'],
            4 => [],
            5 => [],
            6 => [],
            7 => [],
            8 => ['Проанализировать текущие скрипты',
                'Составить новые скрипты, с учетом слабостей существующих.'],
            9 => ['Проанализировать предложения по услуге набора продающих текстов',
                'Заказать продающие тексты'],
            10 => ['Позвонить поставщику и договориться о дополнительных поставках.'],
            11 => ['Купить термометры',
                'Составить график дежурств и назначить дежурных'],
            12 => [],
            13 => [],
            14 => [],
            15 => ['Выставить вакансию на hh.ru']
        ];

        $statuses = ['На рассмотрении', 'В работе', 'На проверке заказчика', 'Решена', 'Удалена'];
        $urgencies = ['Срочная', 'Обычная'];
        $importancies = ['Важная', 'Обычная'];
        $solutionStatuses = [null, 'В процессе', 'Выполнено'];
        $taskStatuses = ['К исполнению', 'В процессе', 'Выполнено'];

        $problemsList = [];
        $solutionList = [];
        $taskList = [];

        for ($i = 1; $i <= 15; $i++) {
            $status = $statuses[array_rand($statuses)];
            $urgency = $urgencies[array_rand($urgencies)];
            $importance = $importancies[array_rand($importancies)];
            $createdAt = $faker->dateTimeBetween('-12 months','-10 day');
            $creatorId = rand(2, 21);
            $solutionDeadline = $faker->dateTimeBetween('-5 day','+20 day');
            $taskDeadline = $faker->dateTimeBetween('-5 months','+20 day');
            if ($status === 'Решена' or $status === 'На проверке заказчика') {
                $solutionsStatus = 'Выполнено';
                $progress = 100;
                $taskStatus = 'Выполнено';
            } else {
                $solutionsStatus = $solutionStatuses[array_rand($solutionStatuses)];
                $progress = rand(0, 100);
                $taskStatus = $taskStatuses[array_rand($taskStatuses)];
            }

            $problemsList[] = [
                'name' => $problems[$i],
                'urgency' => $urgency,
                'importance' => $importance,
                'status' => $status,
                'progress' => $progress,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'creator_id' => $creatorId,
            ];

            $solutionList[] = [
                'name' => $solutions[$i],
                'problem_id' => $i,
                'deadline' => $solutionDeadline,
                'status' => $solutionsStatus,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'executor_id' => rand(2, 21),
            ];

            foreach ($tasks[$i] as $task) {
                $taskList[] = [
                    'description' => $task,
                    'status' => $taskStatus,
                    'deadline' => $taskDeadline,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                    'creator_id' => rand(2, 21),
                    'executor_id' => rand(2, 21),
                    'solution_id' => $i,
                ];
            }
        }

        DB::table('problems')->insert($problemsList);
        DB::table('solutions')->insert($solutionList);
        DB::table('tasks')->insert($taskList);
    }
}
