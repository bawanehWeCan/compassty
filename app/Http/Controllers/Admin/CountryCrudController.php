<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Http\Requests\Admin\CountryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CountryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CountryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
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
        $this->crud->setModel(\App\Models\Country::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/country');
        $this->crud->setEntityNameStrings('country', 'countries');
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
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->getColumns();
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    public function update()
    {
        $this->insertDataWithValidation('update');
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // update the row in the db
        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );
        $this->data['entry'] = $this->crud->entry = $item;
        if (!empty($request->city_en)) {
            $this->data['entry']->cities()->updateOrCreate(['code'=>$request->city_code],[
                'name'=>['en'=>$request->city_en,'ar'=>$request->city_ar],
                'code'=>$request->city_code
            ]);
        }

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CountryRequest::class);

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true, ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true, ]);
        $this->crud->field('code');
        $this->crud->field('digits')->type('number');
        $this->crud->addField([
            'name'        => 'skip_otp',
            'label'       => 'Skip OTP',
            'type'        => 'radio',
            'options'     => [
                1 => "Yes",
                0 => "No"
            ],

        ]);
        CRUD::addField([   // Upload
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'=>true
            ]);
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
        $country = Country::findOrFail(\Route::current()->parameter('id'));
        $this->crud->setValidation(CountryRequest::class);

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true,'value'=>$country->getTranslation('name','en')]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true, 'value'=>$country->getTranslation('name','ar')]);
        $this->crud->field('code')->type('text');
        $this->crud->field('digits')->type('number');
        $this->crud->addField([
            'name'        => 'skip_otp',
            'label'       => 'Skip OTP',
            'type'        => 'radio',
            'options'     => [
                1 => "Yes",
                0 => "No"
            ],

        ]);
        CRUD::addField([   // Upload
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'=>true,'value'=>''
            ]);
            $this->crud->addField(['name' => 'city_en', 'type' => 'text','label'=>'City In English', 'store_in'     => 'name','fake'     => true, ]);
            $this->crud->addField(['name' => 'city_ar', 'type' => 'text','label'=>'City In Arabic', 'store_in'     => 'name','fake'     => true, ]);
            $this->crud->field('city_code')->type('text');
    }

    public function getColumns()
    {
        $this->crud->addColumn(['name' => 'name', 'label'=>'English Name','type'     => 'closure',
        'function' => function(Country $entry) {
            return $entry->getTranslation('name','en');
        }]);
        $this->crud->addColumn(['name' => 'name_ar', 'label'=>'Arabic Name','type'     => 'closure',
        'function' => function(Country $entry) {
            return $entry->getTranslation('name','ar');
        }]);

        $this->crud->column('code');
        $this->crud->column('digits');
        $this->crud->addColumn(['name' => 'skip_otp', 'label'=>'Skip OTP','type'     => 'closure',
        'function' => function(Country $entry) {
            if ($entry->skip_otp==1) {
                return "Yes";
            } else if($entry->skip_otp==0){
                return "No";
            }
        }]);
        $this->crud->addColumn(['name'=>'image','type'=>'image']);
        $this->crud->column('created_at');
        $this->crud->column('updated_at');

    }
    public function insertDataWithValidation($update=null)
    {
        CRUD::setRequest(CRUD::validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = CRUD::getRequest();

        if ($update == 'update') {
            $country = Country::findOrFail(\Route::current()->parameter('id'));
            if($request->has('image')){
                unlink($country->image);
            }
        }
        // Encrypt password if specified.
        CRUD::setRequest($request);
        CRUD::unsetValidation(); // Validation has already been run
    }
    protected function setupDeleteOperation()
    {
        $country = Country::findOrFail(\Route::current()->parameter('id'));
        if ($country) {
            unlink($country->image);
        }
    }

}
