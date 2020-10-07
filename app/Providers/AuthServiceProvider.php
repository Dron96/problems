<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Problem;
use App\Models\Solution;
use App\Models\Task;
use App\Policies\GroupPolicy;
use App\Policies\ProblemPolicy;
use App\Policies\SolutionPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Task::class => TaskPolicy::class,
        Group::class => GroupPolicy::class,
        Solution::class => SolutionPolicy::class,
        Problem::class => ProblemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
