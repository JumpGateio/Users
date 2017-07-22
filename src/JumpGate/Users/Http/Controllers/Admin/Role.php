<?php

namespace JumpGate\Users\Http\Controllers\Admin;

use App\Models\Permission as PermissionModel;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class Role extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(\App\Models\Role::class);
        $this->crud->setRoute('admin/role');
        $this->crud->setEntityNameStrings('role', 'roles');

        $this->crud->enableAjaxTable();

        // $this->setFilters();
        $this->setColumns();
        $this->setFields();
    }

    private function setColumns()
    {
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => 'Name',
            ],
            [
                'name'  => 'description',
                'label' => 'Description',
            ],
            [
                'label'     => 'Permissions', // Table column heading
                'type'      => 'select_multiple',
                'name'      => 'permissions', // the method that defines the relationship in your Model
                'entity'    => 'permissions', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => PermissionModel::class, // foreign key model
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
