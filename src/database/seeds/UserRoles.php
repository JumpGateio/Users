<?php

use JumpGate\Core\Abstracts\Seeder;

class UserRoles extends Seeder
{
    public function run()
    {
        $existingRole = $this->db
            ->table('rbac_roles')
            ->where('slug', 'guest')
            ->first();

        if (is_null($existingRole)) {
            $role = [
                'name'        => 'Guest',
                'slug'        => 'guest',
                'description' => 'Default user role.',
                'created_at'  => date('Y-m-d H:i:s'),
            ];

            $this->db->table('rbac_roles')->insert($role);
        }
    }
}
