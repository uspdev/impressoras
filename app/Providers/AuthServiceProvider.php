<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Printer;
use App\Models\User;
use App\Services\ReplicadoTemp;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('logado', function ($user) {
            return true;
        });

        Gate::define('monitor', function ($user) {
            if(Gate::allows('admin')) return True;

            if (!env('REPLICADO_MONITORES', true))
                $monitores = explode(',', env('MONITORES', ''));
            else
                $monitores = ReplicadoTemp::listarMonitores(22);

            return in_array($user->codpes, $monitores);
        });

        Gate::define('imprime', function (User $user, Printer $printer) {
            if (!empty($printer->rule) and !empty($printer->rule->categories)) {
                foreach($printer->rule->categories as $c) {
                    if($user->hasPermissionTo($c, 'senhaunica')) {
                        return true;
                    }
                }
                return false;
            }
            return true;
        });
    }
}
