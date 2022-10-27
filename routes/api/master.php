<?php



Route::prefix('master')->namespace('Master')->group(function () {
    // User, Role and Permission
    Route::post('user-warehouses/attach', 'UserWarehouseController@attach');
    Route::post('user-warehouses/detach', 'UserWarehouseController@detach');
    Route::patch('user-warehouses/update-default', 'UserWarehouseController@updateDefault');
    Route::apiResource('users', 'UserController');
    Route::get('roles/{roles_id}/permissions', 'RolePermissionController@index');
    Route::patch('roles/{roles_id}/permissions', 'RolePermissionController@togglePermission');
    Route::patch('roles/{roles_id}/permissions/bulk-update', 'RolePermissionController@bulkUpdate');
    Route::apiResource('roles', 'RoleController');
    Route::apiResource('user-invitations', 'UserInvitationController');
    Route::apiResource('user-roles', 'UserRoleController');

    // Master
    Route::apiResource('groups', 'GroupController');
    Route::apiResource('warehouses', 'WarehouseController');
    Route::patch('branch-users/update-default', 'BranchUserController@updateDefault');
    Route::post('branch-users/attach', 'BranchUserController@attach');
    Route::post('branch-users/detach', 'BranchUserController@detach');
    Route::apiResource('branches', 'BranchController');
    // Item
    Route::post('item-groups/attach', 'ItemGroupController@attach');
    Route::post('item-groups/detach', 'ItemGroupController@detach');
    Route::apiResource('item-groups', 'ItemGroupController');
    Route::post('items/{id}/attach-groups', 'ItemGroupController@attach');
    Route::post('items/{id}/detach-groups', 'ItemGroupController@detach');
    Route::post('items/{id}/sync-groups', 'ItemGroupController@sync');
    Route::post('items/bulk', 'ItemController@storeMany');
    Route::post('items/import', 'ItemController@import');
    Route::put('items/bulk', 'ItemController@updateMany');
    Route::patch('items/bulk', 'ItemController@updateMany');
    Route::apiResource('items', 'ItemController');
    // Service
    Route::post('service-groups/attach', 'ServiceGroupController@attach');
    Route::post('service-groups/detach', 'ServiceGroupController@detach');
    Route::apiResource('service-groups', 'ServiceGroupController');
    Route::apiResource('services', 'ServiceController');
    // Customer
    Route::post('customer-groups/attach', 'CustomerGroupController@attach');
    Route::post('customer-groups/detach', 'CustomerGroupController@detach');
    Route::apiResource('customer-groups', 'CustomerGroupController');
    Route::prefix('customers')->name('customers.')->group(function (){
        Route::get('export','CustomerController@exportCustomerExcel');
        Route::post('import', 'CustomerController@importCustomer');
        Route::put('{id}/archive', 'CustomerController@archive');
        Route::patch('{id}/archive', 'CustomerController@archive');
        Route::put('bulk-archive', 'CustomerController@bulkArchive');
        Route::patch('bulk-archive', 'CustomerController@bulkArchive');
        Route::put('{id}/activate', 'CustomerController@activate');
        Route::patch('{id}/activate', 'CustomerController@activate');
        Route::put('bulk-activate', 'CustomerController@bulkActivate');
        Route::patch('bulk-activate', 'CustomerController@bulkActivate');
        Route::put('bulk-delete', 'CustomerController@bulkDelete');
        Route::patch('bulk-delete', 'CustomerController@bulkDelete');
    });
    Route::apiResource('customers', 'CustomerController');
    Route::apiResource('price-list-items', 'PriceListItemController');
    Route::apiResource('price-list-services', 'PriceListServiceController');
    Route::apiResource('pricing-groups', 'PricingGroupController');
    // Supplier
    Route::post('supplier-groups/attach', 'SupplierGroupController@attach');
    Route::post('supplier-groups/detach', 'SupplierGroupController@detach');
    Route::apiResource('supplier-groups', 'SupplierGroupController');
    Route::apiResource('suppliers', 'SupplierController');
    // Expedition
    Route::apiResource('expeditions', 'ExpeditionController');
    // Allocation
    Route::post('allocation-groups/attach', 'AllocationGroupController@attach');
    Route::post('allocation-groups/detach', 'AllocationGroupController@detach');
    Route::apiResource('allocation-groups', 'AllocationGroupController');
    Route::apiResource('allocations', 'AllocationController');

    // Fixed Asset
    Route::get('fixed-assets/depreciation-methods', 'FixedAssetController@depreciationMethodList');
    Route::apiResource('fixed-assets', 'FixedAssetController');
    Route::apiResource('fixed-asset-groups', 'FixedAssetGroupController');
});
