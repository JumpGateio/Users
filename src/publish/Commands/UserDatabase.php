<?php

namespace App\Console\Commands\JumpGate;

use Illuminate\Console\Command;
use JumpGate\Users\Models\Role;
use JumpGate\Users\Models\User\Status;
use Symfony\Component\Process\Process;

class UserDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jumpgate:user-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the database for users.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->verifyConfig();
        $this->seedUserTables();

        $this->info('Finished!');
    }

    /**
     * Make sure the user has updated the config before running.
     */
    private function verifyConfig()
    {
        $updated = $this->confirm('Have you updated your config/jumpgate/users.php config?');

        if (!$updated) {
            $this->comment('Please update your config/jumpgate/users.php config.');
            die;
        }
    }

    /**
     * Set up the default data for user tables.
     */
    private function seedUserTables()
    {
        if (Status::count() === 0) {
            $this->comment('Seeding user_statuses...');
            $this->call('db:seed', ['--class' => '\UserStatus']);
        } else {
            $this->comment('Table user_statuses is not empty.  Skipping seed...');
        }

        if (Role::count() === 0) {
            $this->comment('Seeding rbac_roles...');
            $this->call('db:seed', ['--class' => '\LaratrustSeeder']);
        } else {
            $this->comment('Table rbac_roles is not empty.  Skipping seed...');
        }
    }
}
