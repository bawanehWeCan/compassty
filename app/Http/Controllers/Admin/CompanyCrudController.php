<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Http\Requests\Admin\CompanyRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CompanyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompanyCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\Company::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/company');
        $this->crud->setEntityNameStrings('company', 'companies');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(['name' =>'logo', 'type' => 'image']);
        $this->crud->column('name');
        $this->crud->column('address');
        $this->crud->column('phone');
        $this->crud->column('created_at');
        $this->crud->column('updated_at');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumn(['name' =>'cover_picture', 'lsbel'=>'cover','type' => 'image']);
        $this->crud->addColumn(['name' =>'logo', 'type' => 'image']);
        $this->crud->addColumns([['name' => 'name', 'label'=>'English Name','type'     => 'closure',
        'function' => function(Company $entry) {
            return $entry->getTranslation('name','en');
        }],['name' => 'name_ar', 'label'=>'Arabic Name','type'     => 'closure',
        'function' => function(Company $entry) {
            return $entry->getTranslation('name','ar');
        }]]);
        $this->crud->addColumns([['name' => 'address', 'label'=>'English Address','type'     => 'closure',
        'function' => function(Company $entry) {
            return $entry->getTranslation('address','en');
        }],['name' => 'address_ar', 'label'=>'Arabic Address','type'     => 'closure',
        'function' => function(Company $entry) {
            return $entry->getTranslation('address','ar');
        }]]);
        $this->crud->column('phone');
        $this->crud->addColumns([['name' => 'description', 'label'=>'English Description','type'     => 'closure',
        'function' => function(Company $entry) {
            return $entry->getTranslation('description','en');
        }],['name' => 'description_ar', 'label'=>'Arabic Description','type'     => 'closure',
        'function' => function(Company $entry) {
            return $entry->getTranslation('description','ar');
        }]]);
        $this->crud->addColumns([['name' => 'short_desc', 'label'=>'English Short Description','type'     => 'closure',
        'function' => function(Company $entry) {
            return $entry->getTranslation('short_desc','en');
        }],['name' => 'short_desc_ar', 'label'=>'Arabic Short Description','type'     => 'closure',
        'function' => function(Company $entry) {
            return $entry->getTranslation('short_desc','ar');
        }]]);
        $this->crud->addColumn(['name' => 'category', 'label'=>'Category','type'     => 'closure',
        'function' => function(Company $entry) {
            return $entry?->category?->name;
        }]);
        $this->crud->column('lat');
        $this->crud->column('long');
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
        $this->insertDataWithValidation('update');
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
        $this->crud->setValidation(CompanyRequest::class);

        $this->crud->addField([   // Upload
            'name'      => 'cover_picture',
            'label'     => 'Cover',
            'type'      => 'upload',
            'upload'=>true
            ]);

        $this->crud->addField([   // Upload
            'name'      => 'logo',
            'label'     => 'Logo',
            'type'      => 'upload',
            'upload'=>true
            ]);

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true]);
        $this->crud->addField(['name' => 'address_en', 'type' => 'text','label'=>'English Address']);
        $this->crud->addField(['name' => 'address_ar', 'type' => 'text','label'=>'Arabic Address']);
        $this->crud->addField(['name' => 'address', 'type' => 'hidden' ]);

        $this->crud->addField(['name'=>'phone','type'=>'text']);
        $this->crud->addField(['name' => 'description_en', 'type' => 'textarea','label'=>'English Description']);
        $this->crud->addField(['name' => 'description_ar', 'type' => 'textarea','label'=>'Arabic Description']);
        $this->crud->addField(['name' => 'description', 'type' => 'hidden' ]);

        $this->crud->addField(['name' => 'short_desc_en', 'type' => 'textarea','label'=>'English Short Description']);
        $this->crud->addField(['name' => 'short_desc_ar', 'type' => 'textarea','label'=>'Arabic Short Description']);
        $this->crud->addField(['name' => 'short_desc', 'type' => 'hidden' ]);

        $this->crud->addField(
            [  // Select
                'label'     => "Category",
                'type'      => 'select',
                'name'      => 'category_id', // the db column for the foreign key

                'entity'    => 'category',

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Category", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
        $this->crud->field('lat');
        $this->crud->field('long');

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
        $company = Company::findOrFail(\Route::current()->parameter('id'));

        $this->crud->setValidation(CompanyRequest::class);

        $this->crud->addField([   // Upload
            'name'      => 'cover_picture',
            'label'     => 'Cover',
            'type'      => 'upload',
            'upload'=>true,
            'value'=>''
            ]);

        $this->crud->addField([   // Upload
            'name'      => 'logo',
            'label'     => 'Logo',
            'type'      => 'upload',
            'upload'=>true,
            'value'=>''
            ]);

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true,'value'=>$company->getTranslation('name','en') ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true,'value'=>$company->getTranslation('name','ar')]);
        $this->crud->addField(['name' => 'address_en', 'type' => 'text','label'=>'English Address','value'=>$company->getTranslation('address','en')]);
        $this->crud->addField(['name' => 'address_ar', 'type' => 'text','label'=>'Arabic Address','value'=>$company->getTranslation('address','ar')]);
        $this->crud->addField(['name' => 'address', 'type' => 'hidden' ]);

        $this->crud->addField(['name'=>'phone','type'=>'text']);
        $this->crud->addField(['name' => 'description_en', 'type' => 'textarea','label'=>'English Description','value'=>$company->getTranslation('description','en')]);
        $this->crud->addField(['name' => 'description_ar', 'type' => 'textarea','label'=>'Arabic Description','value'=>$company->getTranslation('description','ar')]);
        $this->crud->addField(['name' => 'description', 'type' => 'hidden' ]);

        $this->crud->addField(['name' => 'short_desc_en', 'type' => 'textarea','label'=>'English Short Description','value'=>$company->getTranslation('short_desc','en')]);
        $this->crud->addField(['name' => 'short_desc_ar', 'type' => 'textarea','label'=>'Arabic Short Description','value'=>$company->getTranslation('short_desc','ar')]);
        $this->crud->addField(['name' => 'short_desc', 'type' => 'hidden' ]);

        $this->crud->addField(
            [  // Select
                'label'     => "Category",
                'type'      => 'select',
                'name'      => 'category_id', // the db column for the foreign key

                'entity'    => 'category',

                // optional - manually specify the related model and attribute
                'model'     => "App\Models\Category", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

                'options'   => (function ($query) {
                    return $query->latest()->get();
                }), //  you can use this to filter the results show in the select
            ]);
        $this->crud->field('lat');
        $this->crud->field('long');

    }


    public function insertDataWithValidation($update=null)
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();
        if ($update == 'update') {
            $company = Company::findOrFail(\Route::current()->parameter('id'));
            if($request->has('cover_picture')){
                unlink($company->cover_picture);
            }
            if($request->has('logo')){
                unlink($company->logo);
            }
        }
        // Encrypt password if specified.
        $this->setInput($request, 'address', 'address_en', 'address_ar');
        $this->setInput($request, 'description', 'description_en', 'description_ar');
        $this->setInput($request, 'short_desc', 'short_desc_en', 'short_desc_ar');
        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run
    }

    public function setInput($request, $value, $valueEn, $valueAr)
    {
        if ($request->input($valueEn) && $request->input($valueAr)) {
            $request->request->set($value, ['en' => $request->input($valueEn), 'ar' => $request->input($valueAr)]);
            $request->request->remove($valueEn);
            $request->request->remove($valueAr);
        }
    }

    protected function setupDeleteOperation()
    {
        $company = Company::findOrFail(\Route::current()->parameter('id'));
        if ($company) {
            unlink($company->cover_picture);
            unlink($company->logo);
        }
    }
}
