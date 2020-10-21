<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'name' => 'Admin',
            'surname' => 'Admin',
            'father_name' => 'Adminovich',
            'email' => 'admin@admin.ru',
            'password' => bcrypt('administrator'),
            'is_admin' => true,
        ];
        DB::table('users')->insert($admin);

        $this->call(GroupSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProblemSeeder::class);
//        $this->call(SolutionSeeder::class);
    }
}
