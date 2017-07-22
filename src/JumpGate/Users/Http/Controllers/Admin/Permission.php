<?php

namespace JumpGate\Users\Http\Controllers\Admin;

use App\Models\Role as RoleModel;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class Permission extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(\App\Models\Permission::class);
        $this->crud->setRoute('admin/permission');
        $this->crud->setEntityNameStrings('permission', 'permissions');

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
            // todo - figure out why this fails
            // [
            //     'label'     => 'Roles', // Table column heading
            //     'type'      => 'select_multiple',
            //     'name'      => 'roles', // the method that defines the relationship in your Model
            //     'entity'    => 'roles', // the method that defines the relationship in your Model
            //     'attribute' => 'name', // foreign key attribute that is shown to user
            //     'model'     => RoleModel::class, // foreign key model
            // ],
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
