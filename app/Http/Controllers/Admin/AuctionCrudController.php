<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuctionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AuctionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuctionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Auction::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/auction');
        CRUD::setEntityNameStrings('مزاد', 'مزادات');
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
        CRUD::column('user_id')->label('معرّف المستخدم');
        CRUD::column('name')->label('اسم العنصر');
        CRUD::column('type')->label('نوع العنصر');
        CRUD::column('description')->label('وصف العنصر');
        CRUD::column('image')->label('رابط الصورة');
        CRUD::column('minimum_bid')->label('أدنى سعر مزاد');
        CRUD::column('increment_amount')->label('قيمة الزيادة في المزاد');
        CRUD::column('highest_bidder_id')->label('معرّف أعلى مزايد');
        CRUD::column('winner_id')->label('معرّف الفائز');
        CRUD::column('start_time')->label('وقت بدء المزاد');
        CRUD::column('end_time')->label('وقت انتهاء المزاد');
        CRUD::column('category_id')->label('معرّف الفئة');
        CRUD::column('status')->label('الحالة');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AuctionRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        // Customizing field labels
        CRUD::field('user_id')->label('معرّف المستخدم');
        CRUD::field('name')->label('اسم العنصر');
        CRUD::field('type')->label('نوع العنصر');
        CRUD::field('description')->label('وصف العنصر');
        CRUD::field('image')->label('رابط الصورة');
        CRUD::field('minimum_bid')->label('أدنى سعر مزاد');
        CRUD::field('increment_amount')->label('قيمة الزيادة في المزاد');
        CRUD::field('highest_bidder_id')->label('معرّف أعلى مزايد');
        CRUD::field('winner_id')->label('معرّف الفائز');
        CRUD::field('start_time')->label('وقت بدء المزاد');
        CRUD::field('end_time')->label('وقت انتهاء المزاد');
        CRUD::field('category_id')->label('معرّف الفئة');
        CRUD::field('status')->label('الحالة');
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
