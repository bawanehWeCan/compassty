<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Http\Requests\Admin\AddressImagesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AddressImagesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AddressImagesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation{
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Image::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/address-images');
        $this->crud->setEntityNameStrings('address images', 'address images');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // $this->crud->addClause('where', 'address_id', '!=', 0);
        $this->crud->addColumn([
            'name'  => 'image',
            'label' => 'Image',
            'type' => 'image',

        ],);
        $this->crud->addColumn([
            'name'  => 'address',
            'label' => 'Address',
            'type'     => 'closure',
            'function' => function(Image $entry) {
                return $entry?->address?->name;
            }]
        );

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
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
        $this->crud->setValidation(AddressImagesRequest::class);

        $this->blogFields();



        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - $this->crud->field('price')->type('number');
         * - $this->crud->addField(['name' => 'price', 'type' => 'number']));
         */
    }

    public function blogFields()
     {
         $this->crud->addField([   // Upload
             'name'      => 'images',
             'label'     => 'Images',
             'type'      => 'upload_multiple',
             'upload'    => true,
         ]);


         $this->crud->addField([   // Upload
            'name'      => 'image',
            'type'      => 'hidden',
        ]);
        $this->crud->addField([
            'name'      => 'company_id',
            'type'      => 'hidden',
            'value'      => 0,
        ]);
        $this->crud->addField(
         [  // Select
             'label'     => "Address",
             'type'      => 'select',
             'name'      => 'address_id', // the db column for the foreign key


             'entity'    => 'address',

             'model'     => "App\Models\Address", // related model
             'attribute' => 'name', // foreign key attribute that is shown to user

             'options'   => (function ($query) {
                  return $query->latest()->get();
              }), //  you can use this to filter the results show in the select
          ],
        );
     }
    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();
        if ($request->has('images')) {
            foreach ($request->images as $k => $image) {


                $request->request->set('image',$image);

                $this->crud->setRequest($request);
                $this->crud->unsetValidation(); // Validation has already been run
                if (count($request->images)-1 === $k) {
                    return $this->traitStore();
                }
                $this->traitStore();
            }
        }


    }
    protected function setupDeleteOperation()
    {
        $image = Image::findOrFail(\Route::current()->parameter('id'));
        if ($image) {
            unlink($image->image);
        }
    }
}
