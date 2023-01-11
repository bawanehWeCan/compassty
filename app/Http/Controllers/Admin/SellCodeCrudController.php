<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Code;
use App\Models\Address;
use App\Models\Country;
use App\Http\Requests\Admin\SellCodeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SellCodeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SellCodeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation{
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Code::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sell-code');
        CRUD::setEntityNameStrings('sell code', 'sell codes');
    }

    protected function setupListOperation()
    {
        CRUD::column('code');
        CRUD::column('type');
        $this->crud->addColumn(['name' => 'user_id', 'label'=>'User','type'     => 'closure',
        'function' => function(Code $entry) {
            return $entry?->user?->name;
        }]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        CRUD::column('code');
        CRUD::column('type');
        $this->crud->addColumn(['name' => 'user_id', 'label'=>'User','type'     => 'closure',
        'function' => function(Code $entry) {
            return $entry?->user?->name;
        }]);
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }
    public function update()
    {
        $this->insertDataWithValidation();
        return $this->traitUpdate();
    }
    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(SellCodeRequest::class);

        CRUD::addField(['name'=>'code','type'=>'hidden']);
        CRUD::addField(['name'=>'type','type'=>'hidden','value'=>'premium']);
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

            $this->crud->addField(
            [  // Select
                'label'     => "Address",
                'type'      => 'select',
                'name'      => 'address_id', // the db column for the foreign key



                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Address", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);

            $this->crud->addField(
                [  // Select
                    'label'     => "Country",
                    'type'      => 'select',
                    'name'      => 'country_id', // the db column for the foreign key



                    // optional - manually specify the related model and attribute
                    'model'     => "App\Models\Country", // related model
                    'attribute' => 'name', // foreign key attribute that is shown to user

                    'options'   => (function ($query) {
                        return $query->latest()->get();
                    }), //  you can use this to filter the results show in the select
                ]);

                $this->crud->addField(
                [  // Select
                    'label'     => "City",
                    'type'      => 'select',
                    'name'      => 'city_id', // the db column for the foreign key



                    // optional - manually specify the related model and attribute
                    'model'     => "App\Models\City", // related model
                    'attribute' => 'name', // foreign key attribute that is shown to user

                    'options'   => (function ($query) {
                        return $query->latest()->get();
                    }), //  you can use this to filter the results show in the select
                ]);
    }


    public function insertDataWithValidation($update=null)
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();

        $data = Code::where('code', $request->code)->first();

        $country_code = Country::where('id', $request->country_id)->pluck('code')->first();
        $city_code = City::where('id', $request->city_id)->pluck('code')->first();

        $country_city = strtoupper($country_code . $city_code);

        if ($data) {

            $newCode = str_replace ('UNIQUE',$country_city, $data->code);

            $request->request->set('code',$newCode);


            $address = Address::find($request->address_id);
            $address->code_id = $data->id;
            $address->save();
            $request->request->remove('address_id');
            $request->request->remove('country_id');
            $request->request->remove('city_id');


        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run
    }
}
}
