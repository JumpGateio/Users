<?php

use JumpGate\Core\Abstracts\Seeder;

class UserRoles extends Seeder
{
    public function run()
    {
        $this->addRoleIfNotExists('guest', 'Guest', 'Default user role.');
        $this->addRoleIfNotExists('admin', 'Admin', 'Site administrator.');
    }

    protected function addRoleIfNotExists($slug, $name, $description)
    {
        $existingRole = $this->db
            ->table('rbac_roles')
            ->where('slug', $slug)
            ->first();

        if (is_null($existingRole)) {
            $role = [
                'name'        => $name,
                'slug'        => $slug,
                'description' => $description,
                'created_at'  => date('Y-m-d H:i:s'),
            ];

            $this->db->table('rbac_roles')->insert($role);
        }
    }
}
