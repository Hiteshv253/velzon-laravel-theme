<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\CustomerMaster\CustomerMasterController;
use App\Http\Controllers\CustomerMaster\CustomerActivityHistoryController;
use App\Http\Controllers\AuditManagement\AuditController;
use App\Http\Controllers\ModuleMaster\ModuleController;
use App\Http\Controllers\ModuleMaster\ModuleCRUDController;
use App\Http\Controllers\ModuleMaster\EmployeeController;
use App\Http\Controllers\ModuleMaster\DepartmentController;
use App\Http\Controllers\ModuleMaster\TeamController;
use App\Http\Controllers\ModuleMaster\DesignationController;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Auth::routes();
//Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Route::group(['middleware' => 'auth'], function () {

    Route::get('user/index_vue', [UserController::class, 'index_vue'])->name('user.index_vue');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('index__', [UserController::class, 'index__'])->name('users.index__');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('users/multi_delete/', [UserController::class, 'multi_delete'])->name('users.multi_delete');
    Route::post('users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');

    //Update User Details
    Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

    //Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
//    customer master
//  Customer Master Routes


    Route::get('/customer-master', [CustomerMasterController::class, 'index'])->name('customer.index');
//    Route::post('/customer-master', [CustomerMasterController::class, 'index'])->name('customer.index');

    Route::get('/customer-master/create', [CustomerMasterController::class, 'create'])->name('customer.create');
    Route::post('/customer-master/store', [CustomerMasterController::class, 'store'])->name('customer.store');
    Route::get('/customer-master/edit/{id}', [CustomerMasterController::class, 'edit'])->name('customer.edit');
    Route::post('/customer-master/update/{id}', [CustomerMasterController::class, 'update'])->name('customer.update');
    Route::get('/customer-master/delete', [CustomerMasterController::class, 'removedata'])->name('customer.delete');
    Route::post('/customer-master/massdelete', [CustomerMasterController::class, 'massremove'])->name('customer.massdelete');
    Route::post('/customer-master/contract', [CustomerMasterController::class, 'add_contract'])->name('customer.contract');
    Route::post('/customer-master/update-contract', [CustomerMasterController::class, 'edit_contract'])->name('customer.edit-contract');
    Route::get('/customer-master/show/{id}', [CustomerMasterController::class, 'show'])->name('customer.view');
    Route::get('/customer-master/delete-contract', [CustomerMasterController::class, 'delete_contract'])->name('customer.contract_delete');
    Route::get('/customer-master/view-activity/{id}', [CustomerMasterController::class, 'view_activity'])->name('customer.view-activity');
    Route::get('/customer-master/get-branch-customer', [CustomerMasterController::class, 'get_customer'])->name('customer.get_customer');
    Route::get('/customer-master/delete-customer', [CustomerMasterController::class, 'delete_customer'])->name('delete-customer');
    Route::post('/customer-master/bulk-remove-customer', [CustomerMasterController::class, 'bulk_remove_customer'])->name('bulk-remove-customer');
    Route::get('/customer-master/customer-report', [CustomerMasterController::class, 'customer_report'])->name('customer.customer-report');
    Route::post('/customer-master/download_customer', [CustomerMasterController::class, 'getDownloadCustomers'])->name('customer.download_customer');
    Route::post('/customer-master/sheet/mail/', [CustomerMasterController::class, 'sendCustomerExcelSheet'])->name('customer.mail_sheet');

    Route::get('/customer/service/history/{customer_id}', [CustomerActivityHistoryController::class, 'index'])->name('customer.services.history');
    Route::post('/customer/service/batchupdate/batchsupdate/{batch}', [CustomerActivityHistoryController::class, 'batchsupdate'])->name('customer.services.batchsupdate');
    Route::get('/customer/service/batchupdate/{batch_id}/{customer_id}/{batchname}/{frequency}/{total_activities}', [CustomerActivityHistoryController::class, 'batchupdate'])->name('customer.services.batchupdate');

    Route::get('/customer/audit/{customer_id}', [
        'as' => 'admin.customer.audit_list',
        'uses' => [CustomerActivityHistoryController::class, 'getCustomerAudit'],
            // 'middleware' => 'can:audit dashboard'
    ]);
    Route::post('/customer/audit/{customer_id}', [
        'as' => 'admin.customer.audit_list',
        'uses' => [CustomerActivityHistoryController::class, 'getCustomerAudit'],
    ]);

//    ModuleController CURD Master*************************************************************************
    Route::resource('module', ModuleController::class)->names([
        'index' => 'module.index',
    ]);
    Route::resource('roles', 'RoleController');

    Route::group(['prefix' => 'modules/module/'], function () {
        // Save filters
        Route::post('savefilter/store', 'SaveFilterController@store')->name('savefilter.store');
        Route::post('savefilter/update/', 'SaveFilterController@update')->name('savefilter.update');
        Route::delete('savefilter/destroy', 'SaveFilterController@destroy')->name('savefilter.destroy');

        // Mail
        Route::post('mail/sendcsv', 'MailController@sendCSV')->name('mail.sendcsv');

        // Employees
        Route::post('employees/store', 'EmployeeController@employeeStore')->name('employees.store');
        Route::put('employees/update', 'EmployeeController@employeeUpdate')->name('employees.update');

        // Departments
        Route::post('departments/store', 'DepartmentController@departmentStore')->name('departments.store');
        Route::put('departments/update', 'DepartmentController@departmentUpdate')->name('departments.update');

        // Team
        Route::post('teams/store', 'TeamController@teamStore')->name('teams.store');
        Route::put('teams/update', 'TeamController@teamUpdate')->name('teams.update');

        // Designation
        Route::post('designations/store', 'DesignationController@designationStore')->name('designations.store');
        Route::put('designations/update', 'DesignationController@designationUpdate')->name('designations.update');

        // Modules
        Route::get('{name}', [ModuleController::class, 'index'])->name('modules.module');
        Route::get('{name}/create', [ModuleController::class, 'create'])->name('modules.module.create');
        Route::post('store', [ModuleController::class, 'store'])->name('modules.module.store');
        Route::delete('{name}/{id}', [ModuleController::class, 'destroy'])->name('modules.module.destroy');
        Route::get('{name}/{id}', [ModuleController::class, 'show'])->name('modules.module.show');
        Route::get('{name}/{id}/edit', [ModuleController::class, 'edit'])->name('modules.module.edit');
        Route::put('update', [ModuleController::class, 'update'])->name('modules.module.update');
        Route::post('massdestroy', [ModuleController::class, 'massDestroy'])->name('modules.module.massdestroy');
    });
});
