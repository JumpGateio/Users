<?php

namespace JumpGate\Users\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

class Token extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(\App\Models\User\Token::class);
        $this->crud->setRoute('admin/token');
        $this->crud->setEntityNameStrings('token', 'tokens');

        $this->crud->enableAjaxTable();

        // $this->setFilters();
        $this->setColumns();
        $this->setFields();
    }

    private function setColumns()
    {
        $this->crud->setColumns([
            [
                'label'     => 'User',
                'type'      => 'select',
                'name'      => 'user_id',
                'entity'    => 'user',
                'attribute' => 'display_name',
                'model'     => 'App\Models\User',
            ],
            [
                'label' => 'Token',
                'name'  => 'token',
            ],
            [
                'label' => 'Created',
                'name'  => 'created_at',
                'type'  => 'datetime',
            ],
            [
                'label' => 'Expires',
                'name'  => 'expires_at',
                'type'  => 'datetime',
            ],
        ]);
    }

    private function setFields()
    {
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => 'Name',
                'type'  => 'text',
            ],
            [
                'name'  => 'description',
                'label' => 'Description',
                'type'  => 'textarea',
            ],
        ]);
    }
}
