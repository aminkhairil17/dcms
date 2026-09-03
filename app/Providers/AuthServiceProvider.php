<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use App\Policies\ActivityPolicy;
use App\Models\Meeting;
use App\Policies\MeetingPolicy;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Activity::class => ActivityPolicy::class,
        Meeting::class => MeetingPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();


        Gate::before(function ($user, $ability) {
            return method_exists($user, 'hasRole') && $user->hasRole('super_admin') ? true : null;
        });
    }
}
