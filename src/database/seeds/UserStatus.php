<?php

use JumpGate\Core\Abstracts\Seeder;

class UserStatus extends Seeder
{
    public function run()
    {
        $this->truncate('user_statuses');

        $statuses = [
            [
                'name'  => 'active',
                'label' => 'Active',
            ],
            [
                'name'  => 'inactive',
                'label' => 'Inactive',
            ],
            [
                'name'  => 'blocked',
                'label' => 'Blocked',
            ],
        ];

        // Add any data to the table.
        $this->db->table('user_statuses')->insert($statuses);
    }
}
