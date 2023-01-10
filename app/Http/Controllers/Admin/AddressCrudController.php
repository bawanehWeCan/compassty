<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Code;
use App\Models\Address;
use App\Models\Country;
use App\Http\Requests\Admin\AddressRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AddressCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AddressCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation{
        store as traitStore;
    }
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
        $this->crud->setModel(\App\Models\Address::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/address');
        $this->crud->setEntityNameStrings('address', 'addresses');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->column('name');
        $this->crud->column('phone_number');
        $this->crud->column('region');
        $this->crud->column('street');
        $this->crud->addColumn(['name' => 'user_id', 'label'=>'User','type'     => 'closure',
        'function' => function(Address $entry) {
            return $entry->user->name;
        }]);
        $this->crud->column('created_at');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumn(['name' => 'icon_id', 'label'=>'Icon','type'     => 'closure',
        'function' => function(Address $entry) {
            return '<img src="'.$entry->icon->icon.'" />';
        }]);
        $this->crud->column('name');
        $this->crud->column('phone_number');
        $this->crud->column('lat');
        $this->crud->column('long');
        $this->crud->column('region');
        $this->crud->column('street');
        $this->crud->column('build_number');
        $this->crud->column('house_number');
        $this->crud->column('floor_number');
        $this->crud->column('note');
        $this->crud->addColumn(['name' => 'code_id', 'label'=>'User','type'     => 'closure',
        'function' => function(Address $entry) {
            return $entry->code->code;
        }]);
        $this->crud->addColumn(['name' => 'country_id', 'label'=>'User','type'     => 'closure',
        'function' => function(Address $entry) {
            return $entry->country->name;
        }]);
        $this->crud->addColumn(['name' => 'city_id', 'label'=>'User','type'     => 'closure',
        'function' => function(Address $entry) {
            return $entry->city->name;
        }]);
        $this->crud->addColumn(['name' => 'user_id', 'label'=>'User','type'     => 'closure',
        'function' => function(Address $entry) {
            return $entry->user->name;
        }]);
        $this->crud->column('created_at');
        $this->crud->column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    public function store()
    {
        $this->insertDataWithValidation();
        return $this->traitStore();
    }

    public function update()
    {
        $this->insertDataWithValidation();
        return $this->traitUpdate();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AddressRequest::class);

        $this->crud->addField([
                'label'     => "Icon",
                'type'      => 'select',
                'name'      => 'icon_id', // the db column for the foreign key

                'entity'    => 'icon',

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Icon", // related model
                'attribute' => 'id', // foreign key attribute that is shown to user

        ]);
        $this->crud->addField(['name'=>'name','type'=>'text']);
        $this->crud->addField(['name'=>'phone_number','type'=>'text']);
        $this->crud->field('lat');
        $this->crud->field('long');
        $this->crud->addField(['name'=>'region','type'=>'text']);
        $this->crud->addField(['name'=>'street','type'=>'text']);
        $this->crud->addField(['name'=>'code_id','type'=>'hidden']);
        $this->crud->field('build_number');
        $this->crud->field('house_number');
        $this->crud->field('floor_number');
        $this->crud->field('note');
        $this->crud->field('country_id');
        $this->crud->field('city_id');
        $this->crud->field('user_id');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - $this->crud->field('price')->type('number');
         * - $this->crud->addField(['name' => 'price', 'type' => 'number']));
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
        $this->setupCreateOperation();
    }

    public function insertDataWithValidation()
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();
                 // DB::beginTransaction();
                 $country_id = $request->country_id;
                 $city_id = $request->city_id;

                 $country_code = Country::where('id', $country_id)->pluck('code')->first();
                 $city_code = City::where('id', $city_id)->pluck('code')->first();


                 $country_city = strtoupper($country_code . $city_code);

                 $random_number = random_int(1000, 9999);
                 $rn = (string) $random_number;


                 if ($this->checkUniqueNumber($rn)) {

                     $code = Code::Where('code', 'UNIQUE' . $rn)->first();
                     if (empty($code) || !$code) {
                         $code = new Code();
                         $code->code = 'UNIQUE' . $rn;
                         $code->type = 'personal';
                         $code->save();
                     }



                 } else {


                     $code_rn = str($country_city)->append($rn);
                     $code = Code::Where('code', $code_rn)->first();

                     if (empty($code) || !$code) {
                         $code = new Code();
                         $code->code = $code_rn;
                         $code->type = 'personal';
                         $code->save();
                     }


                }
        $request->request->set('code_id',$code->id);
        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run
    }

    public function checkUniqueNumber($rn)
    {

        if (($rn[1] == $rn[0] + 1 ||
            $rn[2] == $rn[1] + 1 ||
            $rn[3] == $rn[2] + 1) || ($rn[1] == $rn[0] - 1 ||
            $rn[2] == $rn[1] - 1 ||
            $rn[3] == $rn[2] - 1) || ($rn[0] == $rn[1] ||
            $rn[1] == $rn[2] ||
            $rn[2] == $rn[3])) {
            return true;
        }

        return false;
    }
}
