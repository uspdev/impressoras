<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Assistant;
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
                $monitores = array_unique(array_merge(ReplicadoTemp::listarMonitores(env('IMPRESSORAS_CODSLAMON', 22)), Assistant::pluck('codpes')->toArray()));    // obtém monitores do Replicado e da base local

            return in_array($user->codpes, $monitores);
        });

        Gate::define('gerencia_monitores_locais', function ($user) {
            return (Gate::allows('admin') && env('REPLICADO_MONITORES', ''));
        });

        Gate::define('imprime', function (User $user, Printer $printer) {
            return $printer->allows($user);
        });
    }
}
