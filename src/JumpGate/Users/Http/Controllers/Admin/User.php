<?php

namespace JumpGate\Users\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\DB;
use JumpGate\Users\Models\User\Status;

class User extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(\App\Models\User::class);
        $this->crud->setRoute('admin/user');
        $this->crud->setEntityNameStrings('user', 'users');
        $this->crud->with('details');

        $this->crud->enableAjaxTable();
        $this->crud->allowAccess('show');
        $this->crud->setShowView('admin.user.show');

        $this->setFilters();
        $this->setColumns();
        $this->setFields();
    }

    private function setFilters()
    {
        $this->crud->addFilter(
            [
                'type'  => 'Select2',
                'name'  => 'roles',
                'label' => 'Roles',
            ],
            function () {
                return DB::table('rbac_roles')
                         ->orderBy('name', 'asc')
                         ->pluck('name', 'id')
                         ->toArray();
            });

        $this->crud->addFilter(
            [
                'type'  => 'Select2',
                'name'  => 'permissions',
                'label' => 'Permissions',
            ],
            function () {
                return DB::table('rbac_permissions')
                         ->orderBy('name', 'asc')
                         ->pluck('name', 'id')
                         ->toArray();
            });
    }

    private function setColumns()
    {
        $this->crud->setColumns([
            // [
            //     'name'  => 'display_name',
            //     'label' => 'Name',
            // ],
            [
                'name'  => 'email',
                'label' => 'Email',
            ],
            [
                'label'     => 'Status',
                'type'      => 'select',
                'name'      => 'status_id',
                'entity'    => 'status',
                'attribute' => 'label',
                'model'     => 'JumpGate\Users\Models\User\Status',
            ],
            // [
            //     'label'         => 'Display Name',
            //     'name'          => 'display_name',
            //     'type'          => 'model_function_attribute',
            //     'function_name' => 'details',
            //     'attribute'     => 'display_name',
            // ],
            [
                'label'     => 'Roles', // Table column heading
                'type'      => 'select_multiple',
                'name'      => 'roles', // the method that defines the relationship in your Model
                'entity'    => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => Role::class, // foreign key model
            ],
        ]);
    }

    private function setFields()
    {
        $this->crud->addFields([
            [
                'name'  => 'email',
                'label' => 'Email',
                'type'  => 'email',
            ],
            [
                'name'   => 'display_name',
                'label'  => 'Display Name',
                'type'   => 'text',
                'entity' => 'details',
            ],
            [
                'name'      => 'first_name',
                'label'     => 'First Name',
                'entity'    => 'details',
                'attribute' => 'first_name',
                'type'      => 'text_relationship',
            ],
            [
                'name'      => 'middle_name',
                'label'     => 'Middle Name',
                'entity'    => 'details',
                'attribute' => 'middle_name',
                'type'      => 'text_relationship',
            ],
            [
                'name'      => 'last_name',
                'label'     => 'Last Name',
                'entity'    => 'details',
                'attribute' => 'last_name',
                'type'      => 'text_relationship',
            ],
            [
                'name'      => 'status_id',
                'label'     => 'Status',
                'type'      => 'select2',
                'entity'    => 'status',
                'attribute' => 'label',
                'model'     => Status::class,
            ],
            [
                // two interconnected entities
                'label'             => 'User Roles and Permissions',
                'field_unique_name' => 'user_role_permission',
                'type'              => 'checklist_dependency',
                'name'              => 'roles_and_permissions', // the methods that defines the relationship in your Model
                'subfields'         => [
                    'primary'   => [
                        'label'            => 'Roles',
                        'name'             => 'roles', // the method that defines the relationship in your Model
                        'entity'           => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute'        => 'name', // foreign key attribute that is shown to user
                        'model'            => Role::class, // foreign key model
                        'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns'   => 3, //can be 1,2,3,4,6
                    ],
                    'secondary' => [
                        'label'          => 'Permissions',
                        'name'           => 'permissions', // the method that defines the relationship in your Model
                        'entity'         => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute'      => 'name', // foreign key attribute that is shown to user
                        'model'          => Permission::class, // foreign key model
                        'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3, //can be 1,2,3,4,6
                    ],
                ],
            ],
        ]);
    }

    public function store()
    {
        // Separate the form into the two important pieces.
        $detailsFields = ['display_name', 'first_name', 'middle_name', 'last_name'];

        $details = request()->only($detailsFields);
        $user    = request()->except($detailsFields);

        // Create the user and add their details.
        $user = $this->crud->create($user);
        $user->details()->create($details);

        // Generate an activation token when configured.
        if ($user->status_id !== Status::ACTIVE && config('jumpgate.users.require_email_activation')) {
            $user->generateActivationToken();
        }

        // Make sure the user always has at least the default role.
        if (count(request('roles_show')) === 0) {
            $user->assignRole(config('jumpgate.users.default_group'));
        }

        // Save the data through the cru
        $this->setSaveAction();

        return $this->performSaveAction($user->getKey());
    }

    public function update($id)
    {
        // Separate the form into the two important pieces.
        $detailsFields = ['display_name', 'first_name', 'middle_name', 'last_name'];

        $details = request()->only($detailsFields);
        $user    = request()->except($detailsFields);

        // Create the user and add their details.
        $user = $this->crud->update(request($this->crud->model->getKeyName()), $user);
        $user->details()->update($details);

        // Save the data through the cru
        $this->setSaveAction();

        return $this->performSaveAction();
    }
}
