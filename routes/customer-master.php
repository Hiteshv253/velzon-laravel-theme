
//  Customer Master Routes
    Route::get('/customer-master/approve/{id}', 'hvl\leadmaster\LeadMasterController@add_toCustomer')->name('lead.approve');
    Route::post('/customer-master/updateCustomer/{id}', 'hvl\leadmaster\LeadMasterController@update_toCustomer')->name('customer.approveUpdate');
    Route::get(
            '/customer-master',
            'hvl\customermaster\CustomerMasterController@index'
    )->name('customer.index');
    Route::post(
            '/customer-master',
            'hvl\customermaster\CustomerMasterController@index'
    )->name('customer.index');

    Route::get('/customer-master/create', 'hvl\customermaster\CustomerMasterController@create')->name('customer.create');
    Route::post('/customer-master/store', 'hvl\customermaster\CustomerMasterController@store')->name('customer.store');
    Route::get('/customer-master/edit/{id}', 'hvl\customermaster\CustomerMasterController@edit')->name('customer.edit');
    Route::post('/customer-master/update/{id}', 'hvl\customermaster\CustomerMasterController@update')->name('customer.update');
    Route::get('/customer-master/delete', 'hvl\customermaster\CustomerMasterController@removedata')->name('customer.delete');
    Route::post('/customer-master/massdelete', 'hvl\customermaster\CustomerMasterController@massremove')->name('customer.massdelete');
    Route::post('/customer-master/contract', 'hvl\customermaster\CustomerMasterController@add_contract')->name('customer.contract');
    Route::post('/customer-master/update-contract', 'hvl\customermaster\CustomerMasterController@edit_contract')->name('customer.edit-contract');
    Route::get('/customer-master/show/{id}', 'hvl\customermaster\CustomerMasterController@show')->name('customer.view');
    Route::get('/customer-master/delete-contract', 'hvl\customermaster\CustomerMasterController@delete_contract')->name('customer.contract_delete');
    Route::get('/customer-master/view-activity/{id}', 'hvl\customermaster\CustomerMasterController@view_activity')->name('customer.view-activity');
    Route::get('/customer-master/get-branch-customer', 'hvl\customermaster\CustomerMasterController@get_customer')->name('customer.get_customer');
    Route::get('/customer-master/delete-customer', 'hvl\customermaster\CustomerMasterController@delete_customer')->name('delete-customer');
    Route::post('/customer-master/bulk-remove-customer', 'hvl\customermaster\CustomerMasterController@bulk_remove_customer')->name('bulk-remove-customer');
    Route::get('/customer-master/customer-report', 'hvl\customermaster\CustomerMasterController@customer_report')->name('customer.customer-report');
    Route::post('/customer-master/download_customer', 'hvl\customermaster\CustomerMasterController@getDownloadCustomers')->name('customer.download_customer');
    Route::post('/customer-master/sheet/mail/', 'hvl\customermaster\CustomerMasterController@sendCustomerExcelSheet')->name('customer.mail_sheet');
