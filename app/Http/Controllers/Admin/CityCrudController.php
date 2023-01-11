<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Http\Requests\Admin\CityRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CityCrudController extends CrudController
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
        CRUD::setModel(\App\Models\City::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/city');
        CRUD::setEntityNameStrings('city', 'cities');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->getColumns();

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }
    protected function setupShowOperation()
    {
        $this->getColumns();

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
        CRUD::setValidation(CityRequest::class);

        CRUD::addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true, ]);
        CRUD::addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true, ]);
        CRUD::field('code');
        CRUD::field('country_id');
        $this->crud->addField(
            [  // Select
                'label'     => "Country",
                'type'      => 'select',
                'name'      => 'country_id', // the db column for the foreign key

                'entity'    => 'country',

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Country", // related model
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

        $city = City::findOrFail(\Route::current()->parameter('id'));

        CRUD::setValidation(CityRequest::class);

        CRUD::addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true,'value'=>$city->getTranslation('name','en')]);
        CRUD::addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true, 'value'=>$city->getTranslation('name','ar')]);
        CRUD::field('code');
        CRUD::field('country_id');
        $this->crud->addField(
            [  // Select
                'label'     => "Country",
                'type'      => 'select',
                'name'      => 'country_id', // the db column for the foreign key

                'entity'    => 'country',

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Country", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
    }

    public function getColumns()
    {
        CRUD::addColumn(['name' => 'name', 'label'=>'English Name','type'     => 'closure',
        'function' => function(City $entry) {
            return $entry->getTranslation('name','en');
        }]);
        CRUD::addColumn(['name' => 'name_ar', 'label'=>'English Name','type'     => 'closure',
        'function' => function(City $entry) {
            return $entry->getTranslation('name','ar');
        }]);

        CRUD::addColumn(['country' => 'name', 'label'=>'Country','type'     => 'closure',
        'function' => function(City $entry) {
            return $entry->country->name;
        }]);

       CRUD::column('code');
        CRUD::column('created_at');
        CRUD::column('updated_at');

    }
}
