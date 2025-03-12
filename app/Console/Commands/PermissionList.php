<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class PermissionList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:list {permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista usuários com uma permission.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::whereHas('permissions', function ($query) {
            $guard_name = 'web';
            return $query->where([
                'name' => $this->argument('permission'),
                'guard_name' => $guard_name
            ]);
        })->get();

        if ($users->count()) {
            foreach($users as $u) {
                $this->line($u->codpes);
            }
        }
        else {
            $this->error("Nenhum usuário encontrado.");
        }

        return 0;
    }
}
