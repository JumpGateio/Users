<?php

namespace JumpGate\Users\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class AddPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jumpgate:add-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the user and permission details for the site.';

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    private $db;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $files;

    /**
     * @param \Illuminate\Database\DatabaseManager $db
     * @param \Illuminate\Filesystem\Filesystem    $files
     */
    public function __construct(DatabaseManager $db, Filesystem $files)
    {
        parent::__construct();

        $this->db    = $db;
        $this->files = $files;

        try {
            $this->permissions = $this->db->table('rbac_permissions')->get();
        } catch (\Exception $exception) {
            // Do nothing.
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $addPermission = true;

        // while ($addPermission) {
        //     $addPermission = $this->getPermission();
        // }

        $this->comment('Generating seed file');
        $this->generateSeeder();
        $this->comment('Complete!');
    }

    /**
     * Allow the user to specify any number of permissions to add to the database.
     *
     * @return bool
     */
    protected function getPermission()
    {
        // Get the display name.  This will go in the 'label' field.
        $label = ucwords($this->ask('Specify the display name for a new permission'));

        $name = null;

        // Make sure we get a valid key name for the permission.
        while (is_null($name)) {
            $name = $this->ask('What should the key name be set to?', Str::slug($label));

            // Make sure they approve the details for the new permission.
            if ($this->confirm("Your permission will be added as: \n\tLabel: $label \n\tName:  $name\nSave this permission?", true)) {
                $data = [
                    'name'       => $name,
                    'label'      => $label,
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                // Add it to the database or detail an error has occurred and a new name needs to be supplied.
                try {
                    $this->db->table('rbac_permissions')->insert($data);
                } catch (QueryException $exception) {
                    $this->error('That name is already taken.  Please choose another.');

                    $name = null;
                    continue;
                }

                // Add the permission to our list.
                $this->permissions[] = (object)$data;

                $this->comment('Added!');
            } else {
                return true;
            }
        }

        // Allow them the chance to add another one.
        return $this->confirm('Add another permission?', true);
    }

    /**
     * Create the seeder file so the permissions can be easily added to other environments.
     */
    private function generateSeeder()
    {
        // Get the stub file and format our permissions.
        $stub        = $this->files->get(__DIR__ . '/stubs/Seeder.stub');
        $permissions = $this->permissions->map(function ($permission) {
            return (array)$permission;
        });
        $permissions = $this->export($permissions->toArray(), "\t");

        // Replace the parts of the stub with actual data.
        $patterns = [
            '__NAME__',
            '__TABLE__',
            '__INSERTS__',
        ];

        $replace = [
            'RbacPermissions',
            'rbac_permissions',
            $permissions,
        ];

        $stub = str_replace($patterns, $replace, $stub);

        // Save the file.
        $this->files->put(base_path('database/seeds/RbacPermissions.php'), $stub);
    }

    /**
     * Export the array into a readable format using PHP 5.4+ array syntax.
     *
     * @param        $array
     * @param string $indent
     *
     * @return mixed|string
     */
    private function export($array, $indent = "")
    {
        switch (gettype($array)) {
            case 'string':
                return '"' . addcslashes($array, "\\\$\"\r\n\t\v\f") . '"';
            case 'array':
                $indexed = array_keys($array) === range(0, count($array) - 1);
                $r       = [];
                foreach ($array as $key => $value) {
                    $r[] = "$indent    "
                           . ($indexed ? "" : $this->export($key) . " => ")
                           . $this->export($value, "$indent    ");
                }

                return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
            case 'boolean':
                return $array ? 'true' : 'false';
            default:
                return var_export($array, true);
        }
    }
}
