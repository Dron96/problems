<?php

use App\User;
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
        for($i = 1; $i <= 20; $i++) {
            $user = factory(User::class)->create();
            $user->createToken('authToken')->accessToken;
        }
        $this->call(ProblemSeeder::class);
        $this->call(SolutionSeeder::class);
        $this->call(GroupSeeder::class);
    }
}
