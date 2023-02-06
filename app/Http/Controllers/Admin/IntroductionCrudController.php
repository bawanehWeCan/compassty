<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\IntroductionRequest;
use App\Models\Introduction;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class IntroductionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class IntroductionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
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
        $this->crud->setModel(\App\Models\Introduction::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/introduction');
        $this->crud->setEntityNameStrings('introduction', 'introductions');
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


        $this->getColumns([
            ['name' => 'body', 'label'=>'English Content','type'     => 'closure',
        'function' => function(Introduction $entry) {
            return $entry->getTranslation('body','en');
        }],
        ['name' => 'body_ar', 'label'=>'Arabic Content','type'     => 'closure',
        'function' => function(Introduction $entry) {
            return $entry->getTranslation('body','ar');
        }],
        ]);

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
        $this->crud->setValidation(IntroductionRequest::class);

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Title', 'store_in'     => 'title','fake'     => true, ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Title', 'store_in'     => 'title','fake'     => true, ]);
        $this->crud->addField(['name' => 'body_en', 'type' => 'textarea','label'=>'English Body']);
        $this->crud->addField(['name' => 'body_ar', 'type' => 'textarea','label'=>'Arabic Body']);
        $this->crud->addField(['name' => 'body', 'type' => 'hidden' ]);

        $this->crud->addField([   // Upload
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
        $introduction = Introduction::findOrFail(\Route::current()->parameter('id'));

        $this->crud->addField(['name' => 'en', 'type' => 'text','label'=>'English Title', 'store_in'     => 'title','fake'     => true,'value'=>$introduction->getTranslation('title','en') ]);
        $this->crud->addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Title', 'store_in'     => 'title','fake'     => true,'value'=>$introduction->getTranslation('title','ar') ]);
        $this->crud->addField(['name' => 'body_en', 'type' => 'textarea','label'=>'English Body', 'value'=>$introduction->getTranslation('body','en') ]);
        $this->crud->addField(['name' => 'body_ar', 'type' => 'textarea','label'=>'Arabic Body', 'value'=>$introduction->getTranslation('body','ar') ]);
        $this->crud->addField(['name' => 'body', 'type' => 'hidden' ]);

        $this->crud->addField([
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'=>true,
            'value'=>''
            ]);
            $this->crud->setValidation(IntroductionRequest::class);

    }

    public function getColumns($more=null)
    {

        $this->crud->addColumn(['name' => 'title', 'label'=>'English Title','type'     => 'closure',
        'function' => function(Introduction $entry) {
            return $entry->getTranslation('title','en');
        }]);
        $this->crud->addColumn(['name' => 'title_ar', 'label'=>'Arabic Title','type'     => 'closure',
        'function' => function(Introduction $entry) {
            return $entry->getTranslation('title','ar');
        }]);
        if ($more!==null) {
        $this->crud->addColumns($more);
        }
        $this->crud->addColumn(['name'=>'image','type'=>'image']);

        $this->crud->column('created_at');
        $this->crud->column('updated_at');

    }


    public function insertDataWithValidation($update=null)
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();
        if ($update == 'update') {
            $introduction = Introduction::findOrFail(\Route::current()->parameter('id'));
            if($request->has('image')){
                unlink($introduction->image);
            }
        }
        // Encrypt password if specified.
        $this->setInput($request, 'body', 'body_en', 'body_ar');
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
        $introduction = Introduction::findOrFail(\Route::current()->parameter('id'));
        if ($introduction) {
            unlink($introduction->image);
        }
    }
}
