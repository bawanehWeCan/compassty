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
        $this->crud->setModel(\App\Models\Code::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/sell-code');
        $this->crud->setEntityNameStrings('sell code', 'sell codes');
    }

    protected function setupListOperation()
    {
        $this->crud->addClause('whereDoesntHave', 'user');
        $this->crud->addClause('where', 'code','like',"%UNIQUE%");
        $this->crud->column('code');
        $this->crud->column('type');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->crud->column('code');
        $this->crud->column('type');

        $this->crud->column('created_at');
        $this->crud->column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
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
        $this->crud->setValidation(SellCodeRequest::class);

        $this->crud->addField(['name'=>'code','type'=>'hidden']);
        $this->crud->addField(['name'=>'type','type'=>'hidden','value'=>'premium']);
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
            $addresses = Address::pluck('name','id')->toArray();

            $this->crud->addField(
            [  // Select
                'label'     => "Address",
                'type'      => 'select_from_array',
                'name'      => 'address_id', // the db column for the foreign key

                'options'   =>$addresses,
                'allows_null' => false,
                'default'     => '-',
                'value'     => '-',

            ]);
            $countries = Country::pluck('name','id')->toArray();

            $this->crud->addField(
                [  // Select
                    'label'     => "Country",
                    'type'      => 'select_from_array',
                    'name'      => 'country_id', // the db column for the foreign key

                    'options'   =>$countries,

                    'allows_null' => false,
                    'default'     => '-',
                    'value'     => '-',

                ]);
                $cities = City::pluck('name','id')->toArray();

                $this->crud->addField(
                [  // Select
                    'label'     => "City",
                    'type'      => 'select_from_array',
                    'name'      => 'city_id', // the db column for the foreign key
                    'options'   =>$cities,
                    'allows_null' => false,
                    'default'     => '-',
                    'value'     => '-',
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
