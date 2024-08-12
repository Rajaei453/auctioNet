<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuctiondetailRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AuctiondetailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuctiondetailCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Auctiondetail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/auctiondetail');
        CRUD::setEntityNameStrings('تفاصيل المزاد', 'تفاصيل المزادات');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        // Customizing column labels
        CRUD::column('auction_id')->label('معرّف المزاد');
        CRUD::column('brand')->label('العلامة التجارية');
        CRUD::column('model')->label('النموذج');
        CRUD::column('manufacturing_year')->label('سنة التصنيع');
        CRUD::column('registration_year')->label('سنة التسجيل');
        CRUD::column('engine_type')->label('نوع المحرك');
        CRUD::column('country')->label('البلد');
        CRUD::column('city')->label('المدينة');
        CRUD::column('area')->label('المنطقة');
        CRUD::column('street')->label('الشارع');
        CRUD::column('floor')->label('الطابق');
        CRUD::column('total_area')->label('المساحة الإجمالية');
        CRUD::column('num_bedrooms')->label('عدد غرف النوم');
        CRUD::column('num_bathrooms')->label('عدد الحمامات');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AuctiondetailRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        // Customizing field labels
        CRUD::field('auction_id')->label('معرّف المزاد');
        CRUD::field('brand')->label('العلامة التجارية');
        CRUD::field('model')->label('النموذج');
        CRUD::field('manufacturing_year')->label('سنة التصنيع');
        CRUD::field('registration_year')->label('سنة التسجيل');
        CRUD::field('engine_type')->label('نوع المحرك');
        CRUD::field('country')->label('البلد');
        CRUD::field('city')->label('المدينة');
        CRUD::field('area')->label('المنطقة');
        CRUD::field('street')->label('الشارع');
        CRUD::field('floor')->label('الطابق');
        CRUD::field('total_area')->label('المساحة الإجمالية');
        CRUD::field('num_bedrooms')->label('عدد غرف النوم');
        CRUD::field('num_bathrooms')->label('عدد الحمامات');
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
}
