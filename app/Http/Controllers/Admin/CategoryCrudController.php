<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Requests\CategoryRequest\Admin;
use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
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
        CRUD::setValidation(CategoryRequest::class);

        CRUD::addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true, ]);
        CRUD::addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true, ]);
        $this->crud->addField([   // Upload
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'=>true
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
        $category = Category::findOrFail(\Route::current()->parameter('id'));
        CRUD::setValidation(CategoryRequest::class);

        CRUD::addField(['name' => 'en', 'type' => 'text','label'=>'English Name', 'store_in'     => 'name','fake'     => true,'value'=>$category->getTranslation('name','en')]);
        CRUD::addField(['name' => 'ar', 'type' => 'text','label'=>'Arabic Name', 'store_in'     => 'name','fake'     => true, 'value'=>$category->getTranslation('name','ar')]);
        $this->crud->addField([
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'=>true,
            'value'=>''
            ]);
    }

    public function getColumns()
    {
        CRUD::addColumn(['name' => 'name', 'label'=>'English Name','type'     => 'closure',
        'function' => function(Category $entry) {
            return $entry->getTranslation('name','en');
        }]);
        CRUD::addColumn(['name' => 'name_ar', 'label'=>'English Name','type'     => 'closure',
        'function' => function(Category $entry) {
            return $entry->getTranslation('name','ar');
        }]);

        CRUD::addColumn(['name'=>'image','type'=>'image']);
        CRUD::column('created_at');
        CRUD::column('updated_at');

    }
    protected function setupDeleteOperation()
    {
        $category = Category::findOrFail(\Route::current()->parameter('id'));
        if ($category) {
            unlink($category->image);
        }
    }
}
