<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NotificationRequest;
use App\Models\Notification;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class NotificationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class NotificationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Notification::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/notification');
        CRUD::setEntityNameStrings('notification', 'notifications');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(['name' => 'content', 'label'=>'English Content','type'     => 'closure',
        'function' => function(Notification $entry) {
            return $entry->getTranslation('content','en');
        }]);
        $this->crud->addColumn(['name' => 'content_ar', 'label'=>'Arabic Content','type'     => 'closure',
        'function' => function(Notification $entry) {
            return $entry->getTranslation('content','ar');
        }]);
        $this->crud->addColumn(['name' => 'view', 'label'=>'View','type'     => 'closure',
        'function' => function(Notification $entry) {
            if ($entry->view==1) {
                return "Unread";
            } else if($entry->view==2){
                return "Read";
            }
        }]);
        CRUD::column('user_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }
    protected function setupShowOperation()
    {
        $this->crud->addColumn(['name' => 'content', 'label'=>'English Content','type'     => 'closure',
        'function' => function(Notification $entry) {
            return $entry->getTranslation('content','en');
        }]);
        $this->crud->addColumn(['name' => 'content_ar', 'label'=>'Arabic Content','type'     => 'closure',
        'function' => function(Category $entry) {
            return $entry->getTranslation('content','ar');
        }]);
        $this->crud->addColumn(['name' => 'view', 'label'=>'View','type'     => 'closure',
        'function' => function(Notification $entry) {
            if ($entry->view==1) {
                return "Unread";
            } else if($entry->view==2){
                return "Read";
            }
        }]);
        CRUD::column('user_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }
    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(NotificationRequest::class);

        $this->crud->addField(['name' => 'en', 'type' => 'textarea','label'=>'English Content', 'store_in'     => 'content','fake'     => true, ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'textarea','label'=>'Arabic Content', 'store_in'     => 'content','fake'     => true, ]);
        $this->crud->addField(
            [  // Select
                'label'     => "User",
                'type'      => 'select',
                'name'      => 'user_id', // the db column for the foreign key

                'entity'    => 'user',

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\User", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(NotificationRequest::class);

        $notification = Notification::findOrFail(\Route::current()->parameter('id'));

        $this->crud->addField(['name' => 'en', 'type' => 'textarea','label'=>'English Content', 'store_in'     => 'content','fake'     => true, $notification->getTranslation('content','en')]);
        $this->crud->addField(['name' => 'ar', 'type' => 'textarea','label'=>'Arabic Content', 'store_in'     => 'content','fake'     => true, $notification->getTranslation('content','ar')]);
        $this->crud->addField(
            [  // Select
                'label'     => "User",
                'type'      => 'select',
                'name'      => 'user_id', // the db column for the foreign key

                'entity'    => 'user',

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\User", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
        }
}
